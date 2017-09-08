<?php

\app\assets\desktop\GlobalAsset::register($this);

$this->title = 'Todevise / Elements';

?>
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<script src="https://use.fontawesome.com/9a31e47575.js"></script>
<div class="elements-wrapper">
	<img class="logo-md auto-center" src="/imgs/logo.svg" data-pin-nopin="true">
	<p class="text-center">ELEMENTS</p>	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Tipografía</h1>
				<span class="title-underlined">Roboto Condensed</span>
				<span class="text-title">Body text</span>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vitae semper magna. Mauris at ligula massa, sit amet tincidunt leo. Praesent a magna tellus, ac dignissim risus.</p>
				<span class="text-title mt-20">Categorías y preguntas</span>
				<div class="category">Sizes</div>
				<div class="category">Material</div>
				<div class="question">Which size is this question available?</div>
				<span class="text-title mt-20">Precios</span>
				<div class="big-price">600</div>
				<span class="text-title mt-20">Otros textos</span>
				<div class="small-text">Select an option</div>
				<span class="text-title mt-20">Menu topbar</span>
				<div class="topbar-item">Boxes</div>
				<span class="title-underlined mt-20">Link colours</span>
				<div>
					<a href="" class="link-red">LINK</a>
				</div>
				<div>
					<a href="" class="link-red">Another link</a>
				</div>
			</div>
		</div>
		<div class="row mt-40">
			<div class="col-md-6">
				<span class="title-underlined">UNIVERS LT 49 LIGHTULTRA CN</span>
				<span class="title-product-name">Titulos productos/nombre</span>
			</div>
		</div>
		<div class="row mt-40">
			<div class="col-md-12">
				<h1>Botones</h1>
			</div>
			<div class="col-md-4">
				<span class="title-underlined">Botón pequeño</span>
				<div>
					<button class="btn btn-small btn-red">Save</button>
				</div>
				<div class="mt-10">
					<button class="btn btn-small btn-red disabled">Save</button>
				</div>
			</div>
			<div class="col-md-4">
				<span class="title-underlined">Botón compra</span>
				<div>
					<button class="btn btn-medium btn-red">
						<i class="ion-android-cart cart-icon-btn"></i>
						<span>Add to the cart</span>
					</button>
				</div>
				<div class="mt-10">
					<button class="btn btn-medium btn-red disabled">
						<i class="ion-ios-cart cart-icon-btn"></i>
						<span>Add to the cart</span>
					</button>
				</div>
			</div>
			<div class="col-md-4">
				<span class="title-underlined">Botón grande</span>
				<div>
					<button class="btn btn-red btn-big btn-red">
						<span>Make profile public</span>
					</button>
				</div>
				<div class="mt-10">
					<button class="btn btn-red btn-big disabled">
						<span>Make profile public</span>
					</button>
				</div>
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-3">
				<span class="title-underlined">RRSS</span>
				<div>
					<span class="share-btn">
						<i class="ion-android-share-alt"></i>
					</span>
				</div>
				<div class="mt-20">
					<ul class="social-items">
						<li>
							<a href="#">
								<i class="fa fa-facebook" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a class="twitter" href="#">
								<i class="fa fa-twitter" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a class="google-plus" href="#">
								<i class="fa fa-google-plus" aria-hidden="true"></i>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-pinterest-p" aria-hidden="true"></i>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-3">
				<span class="title-underlined">Love</span>
				<div>
					<button class="btn btn-love">
						<i class="ion-ios-heart-outline"></i>
					</button>	
				</div>
				<div class="mt-10">
					<button class="btn btn-love active">
						<i class="ion-ios-heart-outline"></i>
					</button>	
				</div>
			</div>
			<div class="col-md-3">
				<span class="title-underlined">Save box</span>
				<div>
					<button class="btn btn-save-box">
						<div class="box-icon"></div>
						<span>Save in a box</span>
					</button>	
				</div>
				<div class="mt-10">
					<button class="btn btn-save-box active">
						<div class="box-icon"></div>
						<span>Save in a box</span>
					</button>	
				</div>
			</div>
			<div class="col-md-3">
				<span class="title-underlined">Desplegar</span>
				<div>
					<span class="share-btn">
						<i class="ion-plus"></i>
					</span>
				</div>
				<div class="mt-50">
					<span class="share-btn">
						<i class="ion-minus"></i>
					</span>
				</div>
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-8">
				<span class="title-underlined">Botón creación producto</span>
				<div class="row text-center">
					<a class="big-btn btn btn-default">
						Add a bank account
					</a>				
				</div>
			</div>
			<div class="col-md-2">
				<span class="title-underlined">Avanzar/ir</span>
				<div>
					<button class="btn-red send-btn" ng-click="completeProfileCtrl.save(completeProfileCtrl.form)" ng-disabled="completeProfileCtrl.sendingForm">
						<img src="/imgs/plane.svg" data-pin-nopin="true">
					</button>
				</div>
			</div>
			<div class="col-md-2">
				<span class="title-underlined">Cargar más</span>
				<div>
					<a class="load-more-btn" href="">Load more <i class="ion-chevron-down"></i></a>
				</div>
				<div class="mt-30">
					<a class="load-more-btn active" href="">Load more <i class="ion-chevron-down"></i></a>
				</div>
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-12">
				<span class="title-underlined">ICONOS</span>
				<div class="black-icons">
					<i class="ion-android-close black-icon"></i>
					<i class="ion-android-cancel black-icon"></i>
					<i class="ion-android-add black-icon"></i>
					<i class="ion-ios-plus black-icon"></i>
					<i class="ion-ios-minus black-icon"></i>
					<i class="ion-arrow-down-a black-icon"></i>
					<i class="ion-loop black-icon"></i>
					<i class="ion-android-refresh black-icon"></i>
					<i class="ion-android-done black-icon"></i>
					<i class="ion-ios-search-strong black-icon"></i>
					<i class="ion-information-circled black-icon"></i>
					<i class="ion-arrow-expand black-icon"></i>
					<i class="ion-arrow-move black-icon"></i>
					<i class="ion-arrow-up-b black-icon"></i>
					<i class="ion-arrow-down-b black-icon"></i>
					<i class="ion-ios-gear black-icon"></i>
					<i class="ion-android-cart black-icon"></i>
					<i class="ion-camera black-icon"></i>
					<i class="black-icon">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 26 28" style="enable-background:new 0 0 26 28;" xml:space="preserve">
						<g id="Page-1">
							<g id="REDISEÑO" transform="translate(-2652.000000, -1023.000000)">
								<g id="noun_105496_cc" transform="translate(2627.000000, 1004.000000)">
									<path id="Shape" class="st0" d="M25.2,45.3l2.2-10c0.1-0.4,0.4-0.8,0.9-0.8l12.2-1.3c0.3,0,0.3-0.6,0-0.6l-12.2-1.2
										c-0.4,0-0.8-0.4-0.9-0.8l-2.2-9.9c-0.2-0.8,0.7-1.5,1.4-1.1L50.5,32c0.7,0.4,0.7,1.5,0,1.9L26.7,46.4
										C25.9,46.8,25,46.1,25.2,45.3L25.2,45.3z"/>
								</g>
							</g>
						</g>
</svg>
					</i>
					<i class="ion-forward black-icon"></i>
					<i class="ion-android-star black-icon"></i>
					<i class="ion-android-share-alt black-icon"></i>
					<i class="ion-edit black-icon"></i>
					<i class="ion-android-print black-icon"></i>
					<i class="black-icon box">
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 566.9 358" style="enable-background:new 0 0 566.9 358;" xml:space="preserve">
						<path class="st0" d="M444.9,59.5c-42.7-9.3-85.4-18.6-128-27.9c-3.5-0.8-6.2-0.2-8.9,2.7c-12.9,13.9-26.1,27.5-39.6,41.7
							c-1.7-1.4-3-2.5-4.2-3.6c-13.8-12.5-27.7-25-41.3-37.7c-3.1-2.9-5.7-3.1-9.3-1.2c-35,18.5-70.1,36.9-105.1,55.3
							c-5.4,2.8-5.5,4.4-1.1,8.9c12.9,13.2,25.8,26.5,38.9,39.5c2.9,2.9,4.2,5.8,4.2,10c-0.2,47.3,0,94.6-0.2,142c0,4.3,1.2,6.2,5.6,6.9
							c10.2,1.7,20.2,3.9,30.3,6c33.9,6.8,67.7,13.7,101.6,20.4c6,1.2,7.1,0.2,7.1-5.8c0-14.8,0-29.7,0-44.5c0-30.7-0.1-61.3,0.1-92
							c0-4.1-1.2-6.1-5.3-6.7c-4.1-0.5-8.1-1.8-12.2-2.3c-3.9-0.4-4.8-2.2-4.8-5.9c0.1-24.8,0.1-49.7,0.1-74.5c0-1.6,0.1-3.2,0.2-5.3
							c43.4,10.5,86.2,20.8,129,31.2c-1.6,2.3-3.2,3.4-4.9,4.3c-28.8,16.4-57.5,32.8-86.4,49c-4,2.2-5.6,4.6-5.5,9.3
							c0.2,44.3,0.1,88.6,0.1,133c0,7.8,1,8.4,7.9,4.6c30.9-17,61.8-34.1,92.8-50.9c4.1-2.2,5.5-4.8,5.5-9.4c-0.2-46.8-0.1-93.6-0.2-140.5
							c0-4.1,1.1-7.2,3.8-10.4c11-12.7,21.8-25.6,32.7-38.5C451.5,62.8,450.9,60.8,444.9,59.5z M238.9,252.8c-4.6,7.3-9.2,14.6-14,21.8
							c-3.2,4.9-5.6,4.7-8-0.5c-4.2-9.2-8.3-18.5-12.5-27.7c-2.7-6-2.4-6.6,4.3-6.8c0.5,0,0.9-0.2,1.9-0.5c0-7,0.1-14.2,0-21.3
							c-0.1-4.2,1.1-5.9,5.6-4.5c2.8,0.9,5.9,1.1,8.8,1.5c4.3,0.6,6.3,2.8,6.1,7.4c-0.3,5.5,0.1,11-0.1,16.5c-0.1,3.1,0.4,4.7,4.1,5.2
							C242.1,245,242.7,246.8,238.9,252.8z M264.5,168.7c-34.8-6.8-69.1-13.6-103.5-20.3c-0.1-0.5-0.3-0.9-0.4-1.4
							c34.3-19.6,68.6-39.3,103.9-59.5C264.5,115.1,264.5,141.3,264.5,168.7z"/>
						</svg>
					</i>
					<i class="ion-android-chat black-icon"></i>
					<i class="ion-trash-a black-icon"></i>					
				</div>
				<div class="black-icons">
					<i class="ion-android-close red-icon"></i>
					<i class="ion-android-cancel red-icon"></i>
					<i class="ion-android-add red-icon"></i>
					<i class="ion-ios-plus red-icon"></i>
					<i class="ion-ios-minus red-icon"></i>
					<i class="ion-arrow-down-a red-icon"></i>
					<i class="ion-loop red-icon"></i>
					<i class="ion-android-refresh red-icon"></i>
					<i class="ion-android-done red-icon"></i>
					<i class="ion-ios-search-strong red-icon"></i>
					<i class="ion-information-circled red-icon"></i>
					<i class="ion-arrow-expand red-icon"></i>
					<i class="ion-arrow-move red-icon"></i>
					<i class="ion-arrow-up-b red-icon"></i>
					<i class="ion-arrow-down-b red-icon"></i>
					<i class="ion-ios-gear red-icon"></i>
					<i class="ion-android-cart red-icon"></i>
					<i class="ion-camera red-icon"></i>
					<i class="red-icon">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 26 28" style="enable-background:new 0 0 26 28;" xml:space="preserve">
						<g id="Page-1">
							<g id="REDISEÑO" transform="translate(-2652.000000, -1023.000000)">
								<g id="noun_105496_cc" transform="translate(2627.000000, 1004.000000)">
									<path id="Shape" class="st0" d="M25.2,45.3l2.2-10c0.1-0.4,0.4-0.8,0.9-0.8l12.2-1.3c0.3,0,0.3-0.6,0-0.6l-12.2-1.2
										c-0.4,0-0.8-0.4-0.9-0.8l-2.2-9.9c-0.2-0.8,0.7-1.5,1.4-1.1L50.5,32c0.7,0.4,0.7,1.5,0,1.9L26.7,46.4
										C25.9,46.8,25,46.1,25.2,45.3L25.2,45.3z"/>
								</g>
							</g>
						</g>
</svg>
					</i>
					<i class="ion-forward red-icon"></i>
					<i class="ion-android-star red-icon"></i>
					<i class="ion-android-share-alt red-icon"></i>
					<i class="ion-edit red-icon"></i>
					<i class="ion-android-print red-icon"></i>
					<i class="red-icon box">
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 566.9 358" style="enable-background:new 0 0 566.9 358;" xml:space="preserve">
						<path class="st0" d="M444.9,59.5c-42.7-9.3-85.4-18.6-128-27.9c-3.5-0.8-6.2-0.2-8.9,2.7c-12.9,13.9-26.1,27.5-39.6,41.7
							c-1.7-1.4-3-2.5-4.2-3.6c-13.8-12.5-27.7-25-41.3-37.7c-3.1-2.9-5.7-3.1-9.3-1.2c-35,18.5-70.1,36.9-105.1,55.3
							c-5.4,2.8-5.5,4.4-1.1,8.9c12.9,13.2,25.8,26.5,38.9,39.5c2.9,2.9,4.2,5.8,4.2,10c-0.2,47.3,0,94.6-0.2,142c0,4.3,1.2,6.2,5.6,6.9
							c10.2,1.7,20.2,3.9,30.3,6c33.9,6.8,67.7,13.7,101.6,20.4c6,1.2,7.1,0.2,7.1-5.8c0-14.8,0-29.7,0-44.5c0-30.7-0.1-61.3,0.1-92
							c0-4.1-1.2-6.1-5.3-6.7c-4.1-0.5-8.1-1.8-12.2-2.3c-3.9-0.4-4.8-2.2-4.8-5.9c0.1-24.8,0.1-49.7,0.1-74.5c0-1.6,0.1-3.2,0.2-5.3
							c43.4,10.5,86.2,20.8,129,31.2c-1.6,2.3-3.2,3.4-4.9,4.3c-28.8,16.4-57.5,32.8-86.4,49c-4,2.2-5.6,4.6-5.5,9.3
							c0.2,44.3,0.1,88.6,0.1,133c0,7.8,1,8.4,7.9,4.6c30.9-17,61.8-34.1,92.8-50.9c4.1-2.2,5.5-4.8,5.5-9.4c-0.2-46.8-0.1-93.6-0.2-140.5
							c0-4.1,1.1-7.2,3.8-10.4c11-12.7,21.8-25.6,32.7-38.5C451.5,62.8,450.9,60.8,444.9,59.5z M238.9,252.8c-4.6,7.3-9.2,14.6-14,21.8
							c-3.2,4.9-5.6,4.7-8-0.5c-4.2-9.2-8.3-18.5-12.5-27.7c-2.7-6-2.4-6.6,4.3-6.8c0.5,0,0.9-0.2,1.9-0.5c0-7,0.1-14.2,0-21.3
							c-0.1-4.2,1.1-5.9,5.6-4.5c2.8,0.9,5.9,1.1,8.8,1.5c4.3,0.6,6.3,2.8,6.1,7.4c-0.3,5.5,0.1,11-0.1,16.5c-0.1,3.1,0.4,4.7,4.1,5.2
							C242.1,245,242.7,246.8,238.9,252.8z M264.5,168.7c-34.8-6.8-69.1-13.6-103.5-20.3c-0.1-0.5-0.3-0.9-0.4-1.4
							c34.3-19.6,68.6-39.3,103.9-59.5C264.5,115.1,264.5,141.3,264.5,168.7z"/>
						</svg>
					</i>
					<i class="ion-android-chat red-icon"></i>
					<i class="ion-trash-a red-icon"></i>					
				</div>
				<div class="black-icons">
					<i class="ion-android-close grey-icon"></i>
					<i class="ion-android-cancel grey-icon"></i>
					<i class="ion-android-add grey-icon"></i>
					<i class="ion-ios-plus grey-icon"></i>
					<i class="ion-ios-minus grey-icon"></i>
					<i class="ion-arrow-down-a grey-icon"></i>
					<i class="ion-loop grey-icon"></i>
					<i class="ion-android-refresh grey-icon"></i>
					<i class="ion-android-done grey-icon"></i>
					<i class="ion-ios-search-strong grey-icon"></i>
					<i class="ion-information-circled grey-icon"></i>
					<i class="ion-arrow-expand grey-icon"></i>
					<i class="ion-arrow-move grey-icon"></i>
					<i class="ion-arrow-up-b grey-icon"></i>
					<i class="ion-arrow-down-b grey-icon"></i>
					<i class="ion-ios-gear grey-icon"></i>
					<i class="ion-android-cart grey-icon"></i>
					<i class="ion-camera grey-icon"></i>
					<i class="grey-icon">
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 26 28" style="enable-background:new 0 0 26 28;" xml:space="preserve">
						<g id="Page-1">
							<g id="REDISEÑO" transform="translate(-2652.000000, -1023.000000)">
								<g id="noun_105496_cc" transform="translate(2627.000000, 1004.000000)">
									<path id="Shape" class="st0" d="M25.2,45.3l2.2-10c0.1-0.4,0.4-0.8,0.9-0.8l12.2-1.3c0.3,0,0.3-0.6,0-0.6l-12.2-1.2
										c-0.4,0-0.8-0.4-0.9-0.8l-2.2-9.9c-0.2-0.8,0.7-1.5,1.4-1.1L50.5,32c0.7,0.4,0.7,1.5,0,1.9L26.7,46.4
										C25.9,46.8,25,46.1,25.2,45.3L25.2,45.3z"/>
								</g>
							</g>
						</g>
</svg>
					</i>
					<i class="ion-forward grey-icon"></i>
					<i class="ion-android-star grey-icon"></i>
					<i class="ion-android-share-alt grey-icon"></i>
					<i class="ion-edit grey-icon"></i>
					<i class="ion-android-print grey-icon"></i>
					<i class="grey-icon box">
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 566.9 358" style="enable-background:new 0 0 566.9 358;" xml:space="preserve">
						<path class="st0" d="M444.9,59.5c-42.7-9.3-85.4-18.6-128-27.9c-3.5-0.8-6.2-0.2-8.9,2.7c-12.9,13.9-26.1,27.5-39.6,41.7
							c-1.7-1.4-3-2.5-4.2-3.6c-13.8-12.5-27.7-25-41.3-37.7c-3.1-2.9-5.7-3.1-9.3-1.2c-35,18.5-70.1,36.9-105.1,55.3
							c-5.4,2.8-5.5,4.4-1.1,8.9c12.9,13.2,25.8,26.5,38.9,39.5c2.9,2.9,4.2,5.8,4.2,10c-0.2,47.3,0,94.6-0.2,142c0,4.3,1.2,6.2,5.6,6.9
							c10.2,1.7,20.2,3.9,30.3,6c33.9,6.8,67.7,13.7,101.6,20.4c6,1.2,7.1,0.2,7.1-5.8c0-14.8,0-29.7,0-44.5c0-30.7-0.1-61.3,0.1-92
							c0-4.1-1.2-6.1-5.3-6.7c-4.1-0.5-8.1-1.8-12.2-2.3c-3.9-0.4-4.8-2.2-4.8-5.9c0.1-24.8,0.1-49.7,0.1-74.5c0-1.6,0.1-3.2,0.2-5.3
							c43.4,10.5,86.2,20.8,129,31.2c-1.6,2.3-3.2,3.4-4.9,4.3c-28.8,16.4-57.5,32.8-86.4,49c-4,2.2-5.6,4.6-5.5,9.3
							c0.2,44.3,0.1,88.6,0.1,133c0,7.8,1,8.4,7.9,4.6c30.9-17,61.8-34.1,92.8-50.9c4.1-2.2,5.5-4.8,5.5-9.4c-0.2-46.8-0.1-93.6-0.2-140.5
							c0-4.1,1.1-7.2,3.8-10.4c11-12.7,21.8-25.6,32.7-38.5C451.5,62.8,450.9,60.8,444.9,59.5z M238.9,252.8c-4.6,7.3-9.2,14.6-14,21.8
							c-3.2,4.9-5.6,4.7-8-0.5c-4.2-9.2-8.3-18.5-12.5-27.7c-2.7-6-2.4-6.6,4.3-6.8c0.5,0,0.9-0.2,1.9-0.5c0-7,0.1-14.2,0-21.3
							c-0.1-4.2,1.1-5.9,5.6-4.5c2.8,0.9,5.9,1.1,8.8,1.5c4.3,0.6,6.3,2.8,6.1,7.4c-0.3,5.5,0.1,11-0.1,16.5c-0.1,3.1,0.4,4.7,4.1,5.2
							C242.1,245,242.7,246.8,238.9,252.8z M264.5,168.7c-34.8-6.8-69.1-13.6-103.5-20.3c-0.1-0.5-0.3-0.9-0.4-1.4
							c34.3-19.6,68.6-39.3,103.9-59.5C264.5,115.1,264.5,141.3,264.5,168.7z"/>
						</svg>
					</i>
					<i class="ion-android-chat grey-icon"></i>
					<i class="ion-trash-a grey-icon"></i>					
				</div>
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-12">
				<span class="title-underlined">PALETA DE COLOR</span>
				<div>
					<span class="text-black">Black #1c1919</span>
					<div class="bg-black" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
					<span class="text-dark-red">Dark Red #c91c39</span>
					<div class="bg-dark-red" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
					<span class="text-red">Red #F7284B</span>
					<div class="bg-red" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
					<span class="text-dark-grey">Dark Grey #939393</span>
					<div class="bg-dark-grey" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
					<span class="text-grey">Grey #bfbfbf</span>
					<div class="bg-grey" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
					<span class="text-yellow">Yellow #ffd55d</span>
					<div class="bg-yellow" style="margin:0 10px 0 10px;vertical-align:middle;width:60px;height:30px;display:inline-block;"></div>
				</div>
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-12">
				<span class="title-underlined">Imágenes</span>
			</div>
			<div class="col-md-4">
				<img class="avatar-default big" src="/imgs/default-avatar-grey.png">
			</div>
			<div class="col-md-4">
				<img class="avatar-default medium" src="/imgs/default-avatar-grey.png">
			</div>
			<div class="col-md-4">
				<img class="avatar-default small" src="/imgs/default-avatar-grey.png">
			</div>
			<div class="col-md-4">
				<img class="avatar-default big" src="/imgs/avatar-1.jpg">
			</div>
			<div class="col-md-4">
				<img class="avatar-default medium" src="/imgs/avatar-1.jpg">
			</div>
			<div class="col-md-4">
				<img class="avatar-default small" src="/imgs/avatar-1.jpg">
			</div>
		</div>
		<div class="row mt-30">
			<div class="col-md-12">
				<span class="title-underlined">Inputs</span>
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Input normal">
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Input normal">
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Input normal">
			</div>
		</div>
		<div class="row mt-30 mb-100">
			<div class="col-md-12">
				<span class="title-underlined">Dropdown</span>
			</div>
			<div class="col-md-4">
				<ol class="black-form-select work-field nya-bs-select" name="categories" required multiple>
									<li nya-bs-option="category in completeProfileCtrl.categories" multiple deep-watch="true">
										<a href="#"><span ng-bind="category.name">Oli</span> <span class="check-mark glyphicon glyphicon-ok"></span></a>
									</li>
									<li nya-bs-option="category in completeProfileCtrl.categories" multiple deep-watch="true">
										<a href="#"><span ng-bind="category.name">Oli</span> <span class="check-mark glyphicon glyphicon-ok"></span></a>
									</li>
					<li nya-bs-option="category in completeProfileCtrl.categories" multiple deep-watch="true">
										<a href="#"><span ng-bind="category.name">Oli</span> <span class="check-mark glyphicon glyphicon-ok"></span></a>
									</li>
								</ol>
			</div>
			<div class="col-md-4">
				
			</div>
			<div class="col-md-4">
				
			</div>
		</div>
	</div>	
</div>
