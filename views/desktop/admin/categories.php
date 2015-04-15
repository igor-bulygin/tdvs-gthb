<?php

use app\assets\desktop\admin\CategoriesAsset;

/* @var $this yii\web\View */

CategoriesAsset::register($this);

$this->title = 'Todevise';
?>
<div class="site-index">

	<div class="body-content">

		<div class="row">
			<div class="col-lg-12">
				Categories
				<br />
				<button class="add_actions">Add actions</button>
				<button class="remove_actions">Remove actions</button>

				<div>
					Search:
					<input class="search-input"></input>
				</div>

				<button id="open_all">Open all</button>
				<button id="close_all">Close all</button>

				<div id="container"></div>


				<button id="explain">Explain</button>
				<br />
				<textarea id="explain_text" rows="20" cols="50"></textarea>
			</div>
		</div>

	</div>

</div>
