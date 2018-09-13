<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderPack;
use app\models\OrderProduct;
use app\models\Person;
use Stripe\Stripe;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class CartController extends AppPublicController
{

	public function actionCreateCart()
	{
		$cart = new Order();
		if (!Yii::$app->user->isGuest) {
			$cart->person_id = Yii::$app->user->identity->short_id;
		}
		$cart->subtotal = 0;
		$cart->total = 0;
		$cart->save();

		Yii::$app->response->setStatusCode(201); // Created

		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cart->short_id);
		$cart->setSubDocumentsForSerialize();

		return $cart;
	}

	public function actionView($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cartId);

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		$cart->recalculateAll();
		$cart->save();

		Yii::$app->response->setStatusCode(200); // Ok

		$cart->setSubDocumentsForSerialize();

		return $cart;
	}

	public function actionAddProduct($cartId)
	{
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		$product = new OrderProduct();
		$product->setParentObject($cart);

		if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

			$cart->addProduct($product);

			Yii::$app->response->setStatusCode(201); // Created

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$cart = Order::findOneSerialized($cartId);
			$cart->setSubDocumentsForSerialize();

			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
	}

	public function actionDeleteProduct($cartId, $priceStockId)
	{
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */
		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		$cart->deleteProduct($priceStockId);

		Yii::$app->response->setStatusCode(200); // Ok

		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cartId);
		$cart->setSubDocumentsForSerialize();

		return $cart;
	}

	public function actionUpdate($cartId)
	{
		$cart = Order::findOneSerialized($cartId);
		if (!$cart) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		// only validate received fields (only if we are not changing the state)
		$cart->setScenario(Order::SCENARIO_CART);

		// Get person associated with the cart
		$person = $cart->getPerson();

		// In this method, we only update the next properties:
		// - shipping_address
		// - billing_address
		// - packs.shipping_type

		$post = Yii::$app->request->post();
		if (isset($post['shipping_address'])) {
			$cart->shipping_address = $post['shipping_address'];
		}
		if (isset($post['billing_address'])) {
			$cart->billing_address = $post['billing_address'];
		}
		if (isset($post['packs'])) {
			$packs = $cart->getPacks();
			foreach ($post['packs'] as $packPost) {
				if (isset($packPost['short_id']) && isset($packPost['shipping_type'])) {
					foreach ($packs as $packActual) {
						if ($packActual->short_id == $packPost['short_id']) {
							$packActual->shipping_type = $packPost['shipping_type'];
							$packActual->recalculateTotals();
						}
					}
				}
			}
			$cart->setPacks($packs);
		}

		if ($cart->validate()) {

			$cart->save(false);

			$shipping = $cart->getShippingAddress();
			$person->personalInfoMapping->name = $shipping->name;
			$person->personalInfoMapping->last_name = $shipping->last_name;
			$person->personalInfoMapping->city = $shipping->city;
			$person->personalInfoMapping->country = $shipping->country;
			$person->personalInfoMapping->address = $shipping->address;
			$person->personalInfoMapping->zip = $shipping->zip;
			$person->personalInfoMapping->vat_id = $shipping->vat_id;
			$person->personalInfoMapping->phone_number_prefix = $shipping->phone_number_prefix;
			$person->personalInfoMapping->phone_number = $shipping->phone_number;

      if($shipping->zip != null) { // Prevent reset personal_info data if submit before fill shipping cart info.
        $person->setAttribute('personal_info', $person->personalInfoMapping);
        $person->save(true, ['personal_info', 'available_earnings']);
      }
      else {
        $person->save(true, ['available_earnings']);
      }

			Yii::$app->response->setStatusCode(200); // Ok

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$cart = Order::findOneSerialized($cartId);
			$cart->setSubDocumentsForSerialize();

			return $cart;

		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $cart->errors];
		}
	}

	public function actionReceiveToken($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$order = Order::findOneSerialized($cartId);
		/* @var Order $order */

		if (empty($order)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$order->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		if (!$order->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

    // var_dump(Yii::$app->request->post('token'));
    if(Yii::$app->request->post('token') == $order->short_id."?*s") { // TODO: secure this

      if($order->getPerson()->available_earnings >= $order->total) {
        $pay_with_credit = true;
      } else {
        throw new BadRequestHttpException("There is not enought credit");
      }
    }
    else {
      /*
        {
          "id": "tok_19igOXJt4mveficFYDqFqBcB",
          "object": "token",
          "card": {
            "id": "card_19igOXJt4mveficFdxIMWpw9",
            "object": "card",
            "address_city": null,
            "address_country": null,
            "address_line1": null,
            "address_line1_check": null,
            "address_line2": null,
            "address_state": null,
            "address_zip": "15177",
            "address_zip_check": "pass",
            "brand": "Visa",
            "country": "US",
            "cvc_check": "pass",
            "dynamic_last4": null,
            "exp_month": 5,
            "exp_year": 2020,
            "funding": "credit",
            "last4": "4242",
            "metadata": {},
            "name": "jose.vazquez.viader@gmail.com",
            "tokenization_method": null
          },
          "client_ip": "83.63.169.121",
          "created": 1486025805,
          "email": "jose.vazquez.viader@gmail.com",
          "livemode": false,
          "type": "card",
          "used": false
        }
      */

      $currentPaymentInfo = Yii::$app->request->post('token');
      $token = $currentPaymentInfo['id'];

      if (!$token) {
        $message = sprintf("We didn't received any token from stripe for cart_id %s", $cartId);
        Yii::info($message, 'Stripe');
        throw new BadRequestHttpException("No token received from stripe");
      }

      $pay_with_credit = false;

    }


		if (!$order->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		try {

			Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

			$person = $order->getPerson();

			$packs = $order->getPacks();

			$shippingAddress = $order->getShippingAddress();
			$billingAddress = $order->getBillingAddress();

			// Create a customer in stripe for the received token
      if(!$pay_with_credit) {
        $customer = \Stripe\Customer::create([
          'email' => $person->credentials['email'],
          'business_vat_id' => $billingAddress->vat_id,
          'shipping' => [
            'address' => [
              'line1' => $shippingAddress->address,
              'city' => $shippingAddress->city,
              'country' => $shippingAddress->country,
              'postal_code' => $shippingAddress->zip,
            ],
            'name' => $shippingAddress->getFullName(),
            'phone' => $shippingAddress->getPhone(),
          ],
          'source' => $token,
        ]);
      }

			$charges = [];
      foreach ($packs as $pack) {
        $pack->recalculateTotals();
        $deviser = $pack->getDeviser();

        if(!empty($order->first_discount) && $order->first_discount){
          $stripeAmount = (int)((($pack->pack_price + $pack->shipping_price) - ($pack->pack_price * $order->percent_discount / 100)) * 100);
        } else {
          $stripeAmount = (int)(($pack->pack_price + $pack->shipping_price) * 100);
        }

        $vatOverFeePercentage = $deviser->getVatOverFee();
        if(!empty($order->first_discount) && $order->first_discount){
          $todeviseFeePercentage = $deviser->getTodeviseFeeAfterDiscount(floatval($order->percent_discount/100));
          $totalFeePercentage = $deviser->getSalesApplicationFeeAfterDiscount(floatval($order->percent_discount/100));
        } else {
          $todeviseFeePercentage = $deviser->getTodeviseFee();
          $totalFeePercentage = $deviser->getSalesApplicationFee();
        }

        // var_dump("Pack: ".$pack->short_id." | stripeAmount: ".$stripeAmount);

        $charge_info = array(); // We store all transfers to stripe



        // If the deviser has no a connected stripe account, we charge directly to todevise account
        if (empty($deviser->settingsMapping->stripeInfoMapping->access_token)) {

          if(!$pay_with_credit) { // No need to charge to todevise because it's credit, ( previously charged )
            $charge = \Stripe\Charge::create([
              'customer' => $customer->id,
              'currency' => 'eur',
              'amount' => $stripeAmount,
              "description" => "Order Nº " . $order->short_id . "/" . $pack->short_id,
              "metadata" => [
                "order_id" => $order->short_id,
                "pack_id" => $pack->short_id,
                "person_id" => $person->short_id,
              ],
            ]);
          }

        }
        // We charge to the deviser account
        else {

          $applicationFeeAmount = round($stripeAmount * $totalFeePercentage, 0);

          if(!$pay_with_credit) {
            // Create a Token for the customer on the connected deviser account
            $token = \Stripe\Token::create(
              [
                'customer' => $customer->id,
                'card' => $currentPaymentInfo['card']['id'],
              ],
              [
                // id of the connected account
                'stripe_account' => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
              ]
            );



            // Create a charge for this customer in the connected deviser account
            $charge = \Stripe\Charge::create(
              [
                'source' => $token,
                'currency' => 'eur',
                'amount' => $stripeAmount,
                'description' => 'Order Nº ' . $order->short_id . '/' . $pack->short_id,
                'application_fee' => $applicationFeeAmount,
                'metadata' => [
                  'order_id' => $order->short_id,
                  'pack_id' => $pack->short_id,
                  'person_id' => $person->short_id,
                ],
              ],
              [
                // id of the connected account
                'stripe_account' => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
              ]
            );

            $chargeResponse = [
              'id' => $charge->id,
              'object' => $charge->object,
              'amount' => $charge->amount,
              'amount_refunded' => $charge->amount_refunded,
              'application' => $charge->application,
              'application_fee' => $charge->application_fee,
              'balance_transaction' => $charge->balance_transaction,
              'captured' => $charge->captured,
              'created' => $charge->created,
              'currency' => $charge->currency,
              'customer' => $charge->customer,
              'description' => $charge->description,
              'destination' => $charge->destination,
              'receipt_email' => $charge->receipt_email,
              'status' => $charge->status,
            ];

          }
          else {
            // Make the transfer to the user who earn the amount
            $transfer = \Stripe\Transfer::create(array(
              "amount" => (int)round( ( $stripeAmount * ( 1 - $order->totalFees()) ), 0 , PHP_ROUND_HALF_DOWN),
              "currency" => "eur",
              // "source_transaction" => $charge->id, // Use the amount of previous CHARGE instead the account funds.
              "destination" => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
              "transfer_group" => $order->short_id."-".$pack->short_id,
              "metadata" => [
                    "description" => "Comision Order Nº " . $order->short_id . "/" . $pack->short_id,
                    "order_id" => $order->short_id,
                    "pack_id" => $pack->short_id,
                    "person_id" => $person->short_id,
                  ],
            ));

            $chargeResponse = [
              'id' => $transfer->id,
              'object' => $transfer->object,
              'amount' => $transfer->amount,
              'balance_transaction' => $transfer->balance_transaction,
              'created' => $transfer->created,
              'currency' => $transfer->currency,
              'description' => $transfer->description,
              'destination' => $transfer->destination,
            ];
          }

        }

        array_push($charge_info, $chargeResponse);

        $charge = "";


        // -------------------------------
        // CALCULATE EARNINGS

        // If there is a discount the affiliate earnings won't be calculated.
        if(!isset($order->percent_discount) || $order->percent_discount <= 0) {

          // There aren't a discount, so we calculate the others fees
          $earningsByPack = $order->getEarningsByPack($pack);

          // We distribute the earnings in the pack
          foreach ($earningsByPack as $earner_id => $amount) {
            $earner_id = (string)$earner_id;
            $amount = $amount * 100;

            $chargeResponse = array();

            if($earner_id != 'todevise') {

              $person_earner = Person::findOneSerialized($earner_id);

              if($person_earner != null) {

                // var_dump("earner_id: ".$earner_id." | EARNER_AMOUNT: ". $amount);

                if(!$person_earner->isClient()) { // Deviser or influencer -> try to charge to stripe

                  if(!empty($person_earner->settingsMapping->stripeInfoMapping->access_token)) {

                    // if($person_earner->short_id == $deviser->short_id)
                    //   $amount = $amount + ($pack->shipping_price * 100);

                    if($pay_with_credit) {
                      // Make the transfer to the user who earn the amount
                      $transfer = \Stripe\Transfer::create(array(
                        "amount" => (int)$amount,
                        "currency" => "eur",
                        // "source_transaction" => $charge->id, // Use the amount of previous CHARGE instead the account funds.
                        "destination" => $person_earner->settingsMapping->stripeInfoMapping->stripe_user_id,
                        "transfer_group" => $order->short_id."-".$pack->short_id,
                        "metadata" => [
                              "description" => "Comision Order Nº " . $order->short_id . "/" . $pack->short_id,
                              "order_id" => $order->short_id,
                              "pack_id" => $pack->short_id,
                              "person_id" => $person->short_id,
                            ],
                      ));
                      $chargeResponse = [
                        'id' => $transfer->id,
                        'object' => $transfer->object,
                        'amount' => $transfer->amount,
                        'balance_transaction' => $transfer->balance_transaction,
                        'created' => $transfer->created,
                        'currency' => $transfer->currency,
                        'description' => $transfer->description,
                        'destination' => $transfer->destination,
                      ];
                    }
                    else {
                      // Make the transfer to the user who earn the amount
                      $charge = \Stripe\Charge::create([
                        'customer' => $customer->id,
                        'currency' => 'eur',
                        'amount' => $amount,
                        'description' => 'Comission Order Nº ' . $order->short_id . '/' . $pack->short_id,
                        'destination' => [
                          'account' => $person_earner->settingsMapping->stripeInfoMapping->stripe_user_id
                        ],
                        'metadata' => [
                          'order_id' => $order->short_id,
                          'pack_id' => $pack->short_id,
                          'person_id' => $person->short_id,

                        ],
                      ]);

                      $chargeResponse = [
                        'id' => $charge->id,
                        'object' => $charge->object,
                        'amount' => $charge->amount,
                        'amount_refunded' => $charge->amount_refunded,
                        'application' => $charge->application,
                        'application_fee' => $charge->application_fee,
                        'balance_transaction' => $charge->balance_transaction,
                        'captured' => $charge->captured,
                        'created' => $charge->created,
                        'currency' => $charge->currency,
                        'customer' => $charge->customer,
                        'description' => $charge->description,
                        'destination' => $charge->destination,
                        'receipt_email' => $charge->receipt_email,
                        'status' => $charge->status,
                      ];

                    }

                    array_push($charge_info, $chargeResponse);
                  }
                }
                else { // Earner is a client, so we charge to available_earnings

                  if(isset($person_earner->available_earnings)) {
                    $person_earner->available_earnings = $person_earner->available_earnings + ($amount / 100);
                  }
                  else {
                    $person_earner->available_earnings = ($amount / 100);
                  }

                }

                // Update total_won_so_far only with earned amount by affiliate and discover program
                if(isset($person_earner->total_won_so_far)) {
                  $person_earner->total_won_so_far = $person_earner->total_won_so_far + ($amount / 100);
                }
                else {
                  $person_earner->total_won_so_far = ($amount / 100);
                }
                $person_earner->save(false);
                // -------------------------------------------

              }
            }
          } // End foreach $earningsByPack
        }
        else {
          // var_dump("Hi ha descompte");
        }


        // Set fees
        $pack->pack_percentage_fee = $totalFeePercentage;
        $pack->pack_percentage_fee_todevise = $todeviseFeePercentage;
        $pack->pack_percentage_fee_vat = $vatOverFeePercentage;


        // Recalculate totals
        $pack->pack_total_price = floatval($stripeAmount / 100);
        $pack->pack_total_fee_todevise = round($pack->pack_total_price * $pack->pack_percentage_fee_todevise, 2);
        $pack->pack_total_fee_vat = round($pack->pack_total_fee_todevise * $pack->pack_percentage_fee_vat, 2);
        $pack->pack_total_fee = $pack->pack_total_fee_todevise + $pack->pack_total_fee_vat;


        $pack->charge_info = $charge_info;

        $pack->setState(OrderPack::PACK_STATE_PAID);

        $charges[] = $charge;

      } // End $packs

			$order->setState(Order::ORDER_STATE_PAID);
			$order->save();

			$order->setPacks($packs);

      if($pay_with_credit) {
        $payment_info['type'] = 'by_todevise_credit';
        $order->setAttribute('payment_info', $payment_info);

        // Update available_earnings
        $new_available_earning = $person->available_earnings - $order->total;
        $person->available_earnings = $new_available_earning;
        $person->save();
      }
      else {
        // Save charges responses and payment_info in the order
        $order->setAttribute('payment_info', $currentPaymentInfo);
      }

			$order->save();

			$order->composeEmailOrderPaid(true);

			Yii::$app->response->setStatusCode(200); // Created





      // BUILDING HISTORIC
      // -----------------------------

      // Getting users earnings in this order
      $earningsInOrder = $order->getEarningsByOrder();

      // Set the historic and earnings for each user
      foreach ($earningsInOrder as $per_id => $amount) {
        if($per_id == 'todevise') {
          $personAux = Person::findOneSerialized((string)Yii::$app->params['short_id_todevise_user']);
        }
        else {
          $personAux = Person::findOneSerialized((string)$per_id);
        }

        if($personAux != null) {
          $personAux->setHistoric('earning', $amount, $person->short_id);
          $personAux->setEarningsByUser($order->short_id, $amount, $person->short_id);
        }
      }
      // --------------------





			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$order = Order::findOneSerialized($cartId);
			$order->setSubDocumentsForSerialize();

			return $order;
		} catch (\Exception $e) {
			$message = sprintf("Error in receive-token: " . $e->getMessage());
			Yii::info($message, 'Stripe');

//			$order->setState(Order::ORDER_STATE_FAILED);
//			$order->save();


			throw $e;
		}
	}
}
