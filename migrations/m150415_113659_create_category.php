<?php

use app\models\Lang;
use yii\mongodb\Migration;

class m150415_113659_create_category extends Migration {
	public function up() {
		$en = array_keys(Lang::EN_US_DESC)[0];

		$this->createCollection('category');
		$this->createIndex('category', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('category', 'path');

		$this->insert('category', [
			"short_id" => "1a23b",
			"path" => '/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Art"],
			"slug" => "art"
		]);

		$this->insert('category', [
			"short_id" => "1b34c",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Ceramic"],
			"slug" => "ceramic"
		]);

		$this->insert('category', [
			"short_id" => "1c45d",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Collage"],
			"slug" => "collage"
		]);

		$this->insert('category', [
			"short_id" => "1d56e",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => true,
			"name" => [$en => "Digital"],
			"slug" => "Digital"
		]);

		$this->insert('category', [
			"short_id" => "1e67f",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => true,
			"name" => [$en => "Drawing"],
			"slug" => "drawing"
		]);

		$this->insert('category', [
			"short_id" => "1f78g",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Installation"],
			"slug" => "installation"
		]);

		$this->insert('category', [
			"short_id" => "1g89h",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Mixed Media"],
			"slug" => "mixed-media"
		]);

		$this->insert('category', [
			"short_id" => "1h10i",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => true,
			"name" => [$en => "Painting"],
			"slug" => "painting"
		]);

		$this->insert('category', [
			"short_id" => "1i11j",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => true,
			"name" => [$en => "Photography"],
			"slug" => "photography"
		]);

		$this->insert('category', [
			"short_id" => "1j12k",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => true,
			"name" => [$en => "Printmaking"],
			"slug" => "printmaking"
		]);

		$this->insert('category', [
			"short_id" => "1k13l",
			"path" => '/1a23b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Sculpture"],
			"slug" => "sculpture"
		]);

		$this->insert('category', [
			"short_id" => "2p45q",
			"path" => '/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Industrial Design"],
			"slug" => "industrial-design"
		]);

		$this->insert('category', [
			"short_id" => "2q56r",
			"path" => '/2p45q/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Automotive"],
			"slug" => "Automotive"
		]);

		$this->insert('category', [
			"short_id" => "2r67s",
			"path" => '/2p45q/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Decoration"],
			"slug" => "decoration"
		]);

		$this->insert('category', [
			"short_id" => "2b11c",
			"path" => '/2p45q/2r67s/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Carpets"],
			"slug" => "carpets"
		]);

		$this->insert('category', [
			"short_id" => "2a10b",
			"path" => '/2p45q/2r67s/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Furniture"],
			"slug" => "furniture"
		]);

		$this->insert('category', [
			"short_id" => "2s78t",
			"path" => '/2p45q/2r67s/2a10b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Beds"],
			"slug" => "Beds"
		]);

		$this->insert('category', [
			"short_id" => "2t89v",
			"path" => '/2p45q/2r67s/2a10b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Chairs & Stools"],
			"slug" => "chairs-stools"
		]);

		$this->insert('category', [
			"short_id" => "2v10q",
			"path" => '/2p45q/2r67s/2a10b/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Desks"],
			"slug" => "desks"
		]);

		$this->insert('category', [
			"short_id" => "3f78g",
			"path" => '/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Jewelry"],
			"slug" => "Jewelry"
		]);

		$this->insert('category', [
			"short_id" => "3g02x",
			"path" => '/3f78g/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Bracelets"],
			"slug" => "Bracelets"
		]);

		$this->insert('category', [
			"short_id" => "3h55f",
			"path" => '/3f78g/3g02x/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Bangles"],
			"slug" => "bangles"
		]);

		$this->insert('category', [
			"short_id" => "3n05a",
			"path" => '/3f78g/3g02x/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Beaded Bracelets"],
			"slug" => "beaded-bracelets"
		]);

		$this->insert('category', [
			"short_id" => "3a12b",
			"path" => '/3f78g/3g02x/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Cuffs"],
			"slug" => "cuffs"
		]);

		$this->insert('category', [
			"short_id" => "3op1b",
			"path" => '/3f78g/3g02x/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Friendship Bracelets"],
			"slug" => "friendship-bracelets"
		]);

		$this->insert('category', [
			"short_id" => "3l15v",
			"path" => '/3f78g/3g02x/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Other"],
			"slug" => "other-bracelets"
		]);

		$this->insert('category', [
			"short_id" => "3n05x",
			"path" => '/3f78g/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Brooches"],
			"slug" => "brooches"
		]);

		$this->insert('category', [
			"short_id" => "3abc9",
			"path" => '/3f78g/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Earrings"],
			"slug" => "earrings"
		]);

		$this->insert('category', [
			"short_id" => "3klm5",
			"path" => '/3f78g/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Necklaces"],
			"slug" => "necklaces"
		]);

		$this->insert('category', [
			"short_id" => "3pq54",
			"path" => '/3f78g/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Pendants"],
			"slug" => "Pendants"
		]);

		$this->insert('category', [
			"short_id" => "3145q",
			"path" => '/3f78g/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Rings"],
			"slug" => "rings"
		]);

		$this->insert('category', [
			"short_id" => "3lva9",
			"path" => '/3f78g/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Watches"],
			"slug" => "watches"
		]);

		$this->insert('category', [
			"short_id" => "39sea",
			"path" => '/3f78g/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Other"],
			"slug" => "other-jewelry"
		]);

		$this->insert('category', [
			"short_id" => "4a2b4",
			"path" => '/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Fashion"],
			"slug" => "fashion"
		]);

		$this->insert('category', [
			"short_id" => "4b3c5",
			"path" => '/4a2b4/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Womenswear"],
			"slug" => "womenswear"
		]);

		$this->insert('category', [
			"short_id" => "4c1d2",
			"path" => '/4a2b4/4b3c5/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Jeans"],
			"slug" => "jeans-womens"
		]);

		$this->insert('category', [
			"short_id" => "4d2e3",
			"path" => '/4a2b4/4b3c5/4c1d2/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Bootcut"],
			"slug" => "bootcut-womens"
		]);

		$this->insert('category', [
			"short_id" => "4e6f9",
			"path" => '/4a2b4/4b3c5/4c1d2/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Bootcut Skinny"],
			"slug" => "bootskinny-womens"
		]);

		$this->insert('category', [
			"short_id" => "4x1a2",
			"path" => '/4a2b4/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Menswear"],
			"slug" => "menswear"
		]);

		$this->insert('category', [
			"short_id" => "4x1b3",
			"path" => '/4a2b4/4x1a2/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "T-shirts"],
			"slug" => "tshirts-mens"
		]);

		$this->insert('category', [
			"short_id" => "4x9a5",
			"path" => '/4a2b4/4x1a2/4x1b3/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Crew Necks"],
			"slug" => "crewnecks-mens"
		]);

		$this->insert('category', [
			"short_id" => "4x8b4",
			"path" => '/4a2b4/4x1a2/4x1b3/',
			"sizecharts" => true,
			"prints" => false,
			"name" => [$en => "Polos"],
			"slug" => "polos-mens"
		]);
	}

	public function down() {
		$this->dropAllIndexes('category');
		$this->dropCollection('category');
	}
}
