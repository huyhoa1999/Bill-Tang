<?php

/**
 * Plugin Name:       Create Global Template for NBDesigner Advanced
 * Plugin URI:        https://cmsmart.net
 * Version:           1.2.0
 * Description:       Create templates apply for any products
 * Requires at least: 5.2
 * Requires PHP:      7.1
 * Author:            Hieu + Bie
 * Author URI:        https://cmsmart.net
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nbdesigner-advanced
 * Domain Path:       /languages
 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

add_action('nbd_extra_css','style_ct_api_tommy');
function style_ct_api_tommy($path){
  ?>
        <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) . 'assets/style.css'; ?>">
  <?php
}

class TemplateForNBDesignerAdvanced
{
	private static $somchaiCloudFrontS3URL = '';

	public function __construct()
	{
		register_activation_hook(__FILE__, function () {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();
			$table_name = $wpdb->prefix . "templateall";
			$sql = "CREATE TABLE $table_name (
              id int NOT NULL AUTO_INCREMENT,
              product_id int NOT NULL,
              categories_id varchar(255) ,
              folder text NOT NULL,
              name text ,
              PRIMARY KEY  (id)
            ) $charset_collate;";

			dbDelta($sql);
		});

		add_action('nbd_js_config', array($this, 'nbptemp_all_ai_to_svg'));
		add_filter('nbd_template_ai_all', array($this, "addon_nbd_template_ai_all"));
		add_filter('nbd_select_option_temp', array($this, "addon_select_option_temp"));

		add_action('nbd_update_checkalltemplate', array($this, 'addon_update_checkalltemplate'), 10, 3);

		add_action('nbd_update_checkalltemplate_edit', array($this, 'addon_update_checkalltemplate_edit'), 10, 4);

		add_filter('nbod_get_resource_before_send', array($this, "addon_get_resource_before_send"), 10, 2);
		// add_filter( 'nbod_dislay_kind_of_templates', "addon_dislay_kind_of_templates_ai");
		add_action('nbd_menu', array($this, 'addon_nbd_menus'));
		//add_filter('nbd_add_template_golal', array($this, 'addon_nbd_add_template_golal'), 10, 5);
		add_action('nbd_add_tmp_gol', array($this, 'addon_nbd_add_tmp_gol'));
		add_action('nbd_add_thumail_categories_clipart', array($this, 'addon_add_thumail_categories_clipart'));
		add_action('nbd_save_thumail_categories_template', array($this, 'addon_save_thumail_categories'), 10, 4);
		add_filter('nbdesigner_notices', array($this, 'addon_change_notices'));
		add_filter('adc_edit_customize_tab_clipart', array($this, 'addon_thumail_categories_clipart'));
		//add_action('nbd_add_enable_check_spine', array($this, 'custom_nbd_add_enable_check_spine'));
		add_action('save_post', array($this, 'add_save_price_upload'));
		add_filter('nbd_change_data_tem_by_id', array($this, 'custom_nbd_change_data_tem_by_id'), 10, 3);
		// $class_w = new Wdr\App\Controllers\ManageDiscount();
		// remove_action("woocommerce_cart_item_price",array($class_w, 'getCartPriceHtml'),1000);

		add_filter("woocommerce_cart_item_quantity",array($this,"addon_woocommerce_cart_item_quantity"),100,3);
	}

	public static function nbptemp_all_ai_to_svg()
	{
		echo "var nbptemp_all_ai_to_svg = true;";
	}

	public function addon_woocommerce_cart_item_quantity($product_quantity, $cart_item_key, $cart_item)
	{
		if(isset($cart_item['nbo_meta'])) {
			$option = unserialize(base64_decode($cart_item['nbo_meta']['options']['fields']));
			$quantity_breaks = $option['quantity_breaks'];
			$min_qt = $quantity_breaks[0]['val'];
			$product_quantity = woocommerce_quantity_input(
                array(
                    'input_name'   => "cart[{$cart_item_key}][qty]",
                    'input_value'  => $cart_item['quantity'],
                    'max_value'    => $max_quantity,
                    'min_value'    => $min_qt,
                    'product_name' => $product_name,
                ),
                $_product,
                false
            );
            return $product_quantity;
		}
	}



	public static function addon_nbd_template_ai_all($html)
	{
?>
		<div class="nb-radio">
			<input name="template_preview_type" value="2" ng-model="customTemplate.type" type="radio" id="template_preview_type_2" /> <label for='template_preview_type_2'><?php esc_html_e('Custom', 'web-to-print-online-designer'); ?></label>
		</div>

		<div>
			<label class="template-label">Global Template</label>
			<div class="nb-radio">
				<input name="template_gllll_type" value="3" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_3" /> <label for='template_preview_type_3'><?php esc_html_e('Global Template 1', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="4" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_4" /> <label for='template_preview_type_4'><?php esc_html_e('Global Template 2', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="5" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_5" /> <label for='template_preview_type_5'><?php esc_html_e('Global Template 3', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="6" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_6" /> <label for='template_preview_type_6'><?php esc_html_e('Global Template 4', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="7" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_7" /> <label for='template_preview_type_7'><?php esc_html_e('Global Template 5', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="8" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_8" /> <label for='template_preview_type_8'><?php esc_html_e('Global Template 6', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="9" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_9" /> <label for='template_preview_type_9'><?php esc_html_e('Global Template 7', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="10" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_10" /> <label for='template_preview_type_10'><?php esc_html_e('Global Template 8', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="11" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_11" /> <label for='template_preview_type_11'><?php esc_html_e('Global Template 9', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="12" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_12" /> <label for='template_preview_type_12'><?php esc_html_e('Global Template 10', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="13" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_13" /> <label for='template_preview_type_13'><?php esc_html_e('Global Template 11', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="14" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_14" /> <label for='template_preview_type_14'><?php esc_html_e('Global Template 12', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="15" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_15" /> <label for='template_preview_type_15'><?php esc_html_e('Global Template 13', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="16" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_16" /> <label for='template_preview_type_16'><?php esc_html_e('Global Template 14', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="17" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_17" /> <label for='template_preview_type_17'><?php esc_html_e('Global Template 15', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="18" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_18" /> <label for='template_preview_type_18'><?php esc_html_e('Global Template 16', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="19" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_19" /> <label for='template_preview_type_19'><?php esc_html_e('Global Template 17', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="20" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_20" /> <label for='template_preview_type_20'><?php esc_html_e('Global Template 18', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="21" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_21" /> <label for='template_preview_type_21'><?php esc_html_e('Global Template 19', 'web-to-print-online-designer'); ?></label>
			</div>
			<div class="nb-radio">
				<input name="template_gllll_type" value="22" type="radio" ng-model="checkGlobalTemct.type" id="template_preview_type_22" /> <label for='template_preview_type_22'><?php esc_html_e('Global Template 20', 'web-to-print-online-designer'); ?></label>
			</div>
		</div>

	<?php
	}

	public static function addon_select_option_temp($cats)
	{
		$obj = new stdClass();
		$obj1 = new stdClass();
		$obj2 = new stdClass();
		$obj3 = new stdClass();
		$obj4 = new stdClass();
		$obj5 = new stdClass();
		$obj6 = new stdClass();
		$obj7 = new stdClass();
		$obj8 = new stdClass();
		$obj9 = new stdClass();
		$obj10 = new stdClass();
		$obj11 = new stdClass();
		$obj12 = new stdClass();
		$obj13 = new stdClass();
		$obj14 = new stdClass();
		$obj15 = new stdClass();
		$obj16 = new stdClass();
		$obj17 = new stdClass();
		$obj18 = new stdClass();
		$obj19 = new stdClass();

		$obj->id = 10000;
		$obj->name = 'Global template 1';
		$obj1->id = 10002;
		$obj1->name = 'Global template 2';
		$obj2->id = 10003;
		$obj2->name = 'Global Template 3';

		$obj3->id = 10004;
		$obj3->name = 'Global template 4';
		$obj4->id = 10005;
		$obj4->name = 'Global template 5';
		$obj5->id = 10006;
		$obj5->name = 'Global template 6';
		$obj6->id = 10007;
		$obj6->name = 'Global template 7';
		$obj7->id = 10008;
		$obj7->name = 'Global template 8';
		$obj8->id = 10009;
		$obj8->name = 'Global template 9';
		$obj9->id = 100010;
		$obj9->name = 'Global template 10';
		$obj10->id = 100011;
		$obj10->name = 'Global template 11';
		$obj11->id = 100012;
		$obj11->name = 'Global template 12';
		$obj12->id = 100013;
		$obj12->name = 'Global template 13';
		$obj13->id = 100014;
		$obj13->name = 'Global template 14';
		$obj14->id = 100015;
		$obj14->name = 'Global template 15';
		$obj15->id = 100016;
		$obj15->name = 'Global template 16';
		$obj16->id = 100017;
		$obj16->name = 'Global template 17';
		$obj17->id = 100018;
		$obj17->name = 'Global template 18';
		$obj18->id = 100019;
		$obj18->name = 'Global template 19';
		$obj19->id = 100020;
		$obj19->name = 'Global template 20';

		array_push($cats, $obj);
		array_push($cats, $obj1);
		array_push($cats, $obj2);
		array_push($cats, $obj3);
		array_push($cats, $obj4);
		array_push($cats, $obj5);
		array_push($cats, $obj6);
		array_push($cats, $obj7);
		array_push($cats, $obj8);
		array_push($cats, $obj9);
		array_push($cats, $obj10);
		array_push($cats, $obj11);
		array_push($cats, $obj12);
		array_push($cats, $obj13);
		array_push($cats, $obj14);
		array_push($cats, $obj15);
		array_push($cats, $obj16);
		array_push($cats, $obj17);
		array_push($cats, $obj18);
		array_push($cats, $obj19);
		return $cats;
	}

	public static function addon_update_checkalltemplate($product_id, $folder, $check)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "templateall";
		if (isset($_POST['templateall'])) {
			$cate_id = $_POST['tempcate'];
			$cate_name = $_POST['template_name'];
			$wpdb->insert($table_name, array(
				'product_id'    => $product_id,
				'folder'        => $folder,
				'categories_id' => $cate_id,
				'name' =>    $cate_name,
			));
		}
		return true;
	}

	public static function addon_update_checkalltemplate_edit($task,$product_id, $folder, $check)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "templateall";
		if ($task == "edit" && isset($_POST['templateall'])) {
			$cate_id = $_POST['tempcate'];
			$cate_name = $_POST['template_name'];
			$wpdb->update($table_name, array(
				'product_id'    => $product_id,
				'folder'        => $folder,
				'categories_id' => $cate_id,
				'name' =>    $cate_name,
			),array('folder'=>$folder));
		}
		return true;
	}

	public static function addon_get_resource_before_send($aray, $type)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "templateall";
		$template_data = $wpdb->get_results("SELECT * FROM " . $table_name, OBJECT);
		if ($type == 'get_ai_template') {
			if ($_POST['id'] == '10000') {
				$cate_id = 'template_preview_type_3';
			} elseif ($_POST['id'] == '10002') {
				$cate_id = 'template_preview_type_4';
			} elseif ($_POST['id'] == '10003') {
				$cate_id = 'template_preview_type_5';
			} elseif ($_POST['id'] == '10004') {
				$cate_id = 'template_preview_type_6';
			} elseif ($_POST['id'] == '10005') {
				$cate_id = 'template_preview_type_7';
			} elseif ($_POST['id'] == '10006') {
				$cate_id = 'template_preview_type_8';
			} elseif ($_POST['id'] == '10007') {
				$cate_id = 'template_preview_type_9';
			} elseif ($_POST['id'] == '10008') {
				$cate_id = 'template_preview_type_10';
			} elseif ($_POST['id'] == '10009') {
				$cate_id = 'template_preview_type_11';
			} elseif ($_POST['id'] == '100010') {
				$cate_id = 'template_preview_type_12';
			} elseif ($_POST['id'] == '100011') {
				$cate_id = 'template_preview_type_13';
			} elseif ($_POST['id'] == '100012') {
				$cate_id = 'template_preview_type_14';
			} elseif ($_POST['id'] == '100013') {
				$cate_id = 'template_preview_type_15';
			} elseif ($_POST['id'] == '100014') {
				$cate_id = 'template_preview_type_16';
			} elseif ($_POST['id'] == '100015') {
				$cate_id = 'template_preview_type_17';
			} elseif ($_POST['id'] == '100016') {
				$cate_id = 'template_preview_type_18';
			} elseif ($_POST['id'] == '100017') {
				$cate_id = 'template_preview_type_19';
			} elseif ($_POST['id'] == '100018') {
				$cate_id = 'template_preview_type_20';
			} elseif ($_POST['id'] == '100019') {
				$cate_id = 'template_preview_type_21';
			} elseif ($_POST['id'] == '100020') {
				$cate_id = 'template_preview_type_22';
			}
			foreach ($template_data as $key => $value) {
				if ($value->categories_id == $cate_id) {
					$thumbnail = NBDESIGNER_DATA_URL . 'designs/' . $value->folder . '/preview/frame_0.png';
					$listConfig = self::getListConfig($value);
					array_push($aray['data'], array(
						'pro' => $listConfig ? $listConfig->pro : null,
						'price' => $listConfig ? $listConfig->price : null,
						'proDesign' => $listConfig ? $listConfig->proDesign : null,
						'priceDesign' => $listConfig ? $listConfig->priceDesign : null,
						'id' => $value->folder,
						'name' => $value->name,
						'thumbnail' => Nbdesigner_IO::wp_convert_path_to_url($thumbnail),
						'flag' => 1,
						'src' => Nbdesigner_IO::wp_convert_path_to_url($thumbnail)
					));
				}
			}
		}

		if ($type == 'get_cate_ai_template') {
			foreach ($template_data as $key => $value) {
				if ($value->categories_id != 0) {
					$thumbnail = NBDESIGNER_DATA_URL . 'designs/' . $value->folder . '/preview/frame_0.png';
					$listConfig =  self::getListConfig($value);
					array_push($aray['data'], array(
						'pro' => $listConfig ? $listConfig->pro : null,
						'price' => $listConfig ? $listConfig->price : null,
						'proDesign' => $listConfig ? $listConfig->proDesign : null,
						'priceDesign' => $listConfig ? $listConfig->priceDesign : null,
						'cate_id' => $value->categories_id,
						'id' => $value->folder,
						'name' => $value->name,
						'thumbnail' => Nbdesigner_IO::wp_convert_path_to_url($thumbnail),
						'flag' => 1,
						'src' => Nbdesigner_IO::wp_convert_path_to_url($thumbnail)
					));
				}
			}
		}

		return $aray;
	}

	public static function getListConfig($data)
	{
		$name = $data->folder;
		$path_config = NBDESIGNER_DATA_URL . 'designs' . '/' . $name . '/config.json';
		$response = wp_remote_get($path_config);

		if (!is_wp_error($response) && $response['response']['code'] === 200) {
			$body = wp_remote_retrieve_body($response);
			$listConfig = json_decode($body);

			return $listConfig;
		}

		return false;
	}

	public static function addon_dislay_kind_of_templates_ai($valid_license)
	{
		// TODO:
		$args = array(
			'taxonomy'   => "product_cat",
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
		);
		$product_categories = get_terms($args);
		$select = '<option value="all-cate"> --- Categories templates --- </option>';
		foreach ($product_categories as $key => $value) {
			$select .= '<option value="' . $value->term_id . '"><span>Categories ' . $value->name . '</span></option>';
		}
	?>
		<div ng-if="showglobal == false" ng-style="{'display': settings.task == 'create_template' ? 'none' : 'inline-block' }" class="item" ng-repeat="temp in resource.templates | limitTo: resource.templateLimit" ng-click="insertTemplate(false, temp)">
			<div class="main-item">
				<div class="item-img" nbd-template-hover="{{temp.id}}">
					<img ng-src="{{temp.thumbnail}}" alt="<?php esc_html_e('Template', 'web-to-print-online-designer'); ?>">
				</div>
			</div>
		</div>
		<hr ng-show="resource.templates.length > 0 && resource.globalTemplate.data.length > 0 " class="seperate2" />
		<div ng-if="showglobal == false" class="item" ng-repeat="temp in resource.globalTemplate.data" ng-click="insertGlobalTemplate(temp.id, $index)">
			<div class="main-item" image-on-load="temp.thumbnail">
				<div class="item-img item-img-global-tem">
					<img ng-src="{{temp.thumbnail}}" alt="{{temp.name}}">
					<?php if (!$valid_license) : ?>
						<span class="nbd-pro-mark-wrap" ng-if="$index > 4">
							<svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10">
								<path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path>
							</svg>
							<?php esc_html_e('Pro', 'web-to-print-online-designer'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<select ng-show="showglobal == true && resource.globalTemplate.checkcate" class="process-select select-global-tem-cat" id="category_template_gol">
			<?php echo $select; ?>
		</select>
		<div ng-if="showglobal == false" class="item" ng-repeat="temp in resource.globalTemplate.data" ng-click="insertTemplate(false, temp)">
			<div class="main-item" image-on-load="temp.thumbnail">
				<div class="item-img item-img-global-tem">
					<img ng-src="{{temp.thumbnail}}" alt="{{temp.name}}">
					<?php if (!$valid_license) : ?>
						<span class="nbd-pro-mark-wrap" ng-if="$index > 4">
							<svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10">
								<path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path>
							</svg>
							<?php esc_html_e('Pro', 'web-to-print-online-designer'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div ng-show="showglobal && resource.globalTemplate.checkai" class="item" ng-repeat="temp in resource.globalTemplate.data" ng-click="insertTemplate(false, temp)">
			<div class="main-item" image-on-load="temp.thumbnail">
				<div class="item-img item-img-global-tem">
					<img ng-src="{{temp.thumbnail}}" alt="{{temp.name}}">
					<?php if (!$valid_license) : ?>
						<span class="nbd-pro-mark-wrap" ng-if="$index > 4">
							<svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10">
								<path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path>
							</svg>
							<?php esc_html_e('Pro', 'web-to-print-online-designer'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div ng-show="showglobal && resource.globalTemplate.checkcate && showallcate" class="item" ng-repeat="temp in resource.globalTemplate.data" ng-click="insertTemplate(false, temp)">
			<div class="main-item" image-on-load="temp.thumbnail">
				<div class="item-img item-img-global-tem">
					<img ng-src="{{temp.thumbnail}}" alt="{{temp.name}}">
					<?php if (!$valid_license) : ?>
						<span class="nbd-pro-mark-wrap" ng-if="$index > 4">
							<svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10">
								<path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path>
							</svg>
							<?php esc_html_e('Pro', 'web-to-print-online-designer'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div ng-show="showglobal && resource.globalTemplate.checkcate && golbalecate == temp.cate_id && temp.checkcate && !showallcate" class="item" ng-repeat="temp in resource.globalTemplate.data" ng-click="insertTemplate(false, temp)">
			<div class="main-item" image-on-load="temp.thumbnail">
				<div class="item-img item-img-global-tem">
					<img ng-src="{{temp.thumbnail}}" alt="{{temp.name}}">
					<?php if (!$valid_license) : ?>
						<span class="nbd-pro-mark-wrap" ng-if="$index > 4">
							<svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10">
								<path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path>
							</svg>
							<?php esc_html_e('Pro', 'web-to-print-online-designer'); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php
	}

	public static function addon_nbd_menus()
	{
		add_submenu_page('nbdesigner', esc_html__('Global Template ', 'web-to-print-online-designer'), esc_html__('Global Template', 'web-to-print-online-designer'), 'manage_nbd_tool', 'nbdesigner_template', array(__CLASS__, 'nbdesigner_template'));
	}

	public static function nbdesigner_template()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "templateall";
		$table_product = $wpdb->prefix . "wc_product_meta_lookup";
		$template_data = $wpdb->get_results("SELECT * FROM " . $table_name, OBJECT);
		if (isset($_POST['text_seach'])) {
			$search = $_POST['text_seach'];
			$template_data_seach = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}templateall WHERE name like '%$search%'", OBJECT);
			foreach ($template_data_seach as $key => $value) {
				$name = $value->folder;
				$name_temp = $value->name;
				$thumbnail = NBDESIGNER_DATA_DIR . '/designs/' . $name . '/preview/frame_0.png';
				$thumbnail = Nbdesigner_IO::wp_convert_path_to_url($thumbnail);
				$link_edit_template = add_query_arg(array(
					'product_id'    => $value->product_id,
					'task'          => 'edit',
					'design_type'          => 'template',
					'nbd_item_key'          => $name,
					'rd'            => 'admin_templates'
				), getUrlPageNBD('create'));
				if ($_POST['text_seach'] != '') {
					$id = $value->categories_id;
					$term = get_term_by('id', $id, 'product_cat');
					$tbodyseach .= '<tr>
                                    <td>
                                        <strong>' . $value->id . '</strong>
                                    </td>
                                    <td>
                                        <img style="width:60px;height: 48px;" class="nbd_column_folder_img" src="' . $thumbnail . '">
                                    </td>
                                    <td>
                                        <strong>' . $name_temp . '</strong>
                                    </td>
                                    <td>
                                        <strong>' . $term->name . '</strong>
                                    </td>
                                    <td>
                                        <div class="">
                                            <span class="edit">
                                                <a href="' . $link_edit_template . '">' . __('Edit', 'woocommerce') . '</a> | 
                                            </span>
                                            <span class="delete">
                                                <a style="color: red;" class="delete" href="' . esc_url(add_query_arg('delete', $value->id, '?page=nbdesigner_template')) . '">' . __('Delete', 'woocommerce') . '</a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>';
				}
			}
		}
		$template_product = $wpdb->get_results("SELECT product_id FROM " . $table_product, OBJECT);
		$newURL =  "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]" . "?page=nbdesigner_template";
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url = parse_url($actual_link, PHP_URL_QUERY);
		parse_str($url, $output);
		if ($output['delete']) {
			$id_delete = $output['delete'];
			$wpdb->delete($table_name, array('id' => $id_delete));
			header('Location: ' . $newURL);
		}
		foreach ($template_product as $key => $value) {
			$product_id = $value->product_id;
		}
		$link_add_template = add_query_arg(array(  // TODO:
			'product_id'    => $product_id,
			'task'          => 'create',
			'global'          => 'global',
			'rd'            => 'admin_templates'
		), getUrlPageNBD('create'));
		$args = array(
			'taxonomy'   => "product_cat",
			'number'     => $number,
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'include'    => $ids
		);
		$product_categories = get_terms($args);
		$select = '<option value="0">' . __('--Select Option Categories--') . '</option><option value="template_preview_type_3">' . __('Template Global wwwwwwwwwwwwww') . '</option><option value="template_preview_type_4">' . __('Template Global 2') . '</option><option value="template_preview_type_5">' . __('Template Global 3') . '</option>';
		foreach ($product_categories as $key => $value) {
			$select .= '<option value="' . $value->term_id . '">Categories ' . $value->name . '</option>';
		}
		foreach ($template_data as $key => $value) {
			$name = $value->folder;
			$name_temp = $value->name;
			$thumbnail = NBDESIGNER_DATA_URL . 'designs/' . $value->folder . '/preview/frame_0.png';
			$link_edit_template = add_query_arg(array(
				'product_id'    => $value->product_id,
				'task'          => 'edit',
				'design_type'          => 'template',
				'nbd_item_key'          => $name,
				'rd'            => 'admin_templates'
			), getUrlPageNBD('create'));

			if (isset($_POST['select_cate'])) {
				$seach = $_POST['select_cate'];
			} else {
				$seach = 0;
			}
			if ($value->categories_id == $seach) {
				$tbodyhtml .= '<tr>
								<td>
									<strong>' . $value->id . '</strong>
								</td>
								<td>
									<img style="width:60px;height: 48px;" class="nbd_column_folder_img" src="' . $thumbnail . '">
								</td>
								<td>
									<strong>' . $name_temp . '</strong>
								</td>
								<td>
									<div class="">
										<span class="edit">
											<a href="' . $link_edit_template . '">' . __('Edit', 'woocommerce') . '</a> | 
										</span>
										<span class="delete">
											<a style="color: red;" class="delete" href="' . esc_url(add_query_arg('delete', $value->id, '?page=nbdesigner_template')) . '">' . __('Delete', 'woocommerce') . '</a>
										</span>
									</div>
								</td>
							</tr>';
			}
		}
	?>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<script src=" https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"></script>
		<script src="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#dtBasicExample').DataTable();
				$('.dataTables_length').addClass('bs-select');
			});
		</script>
		<style type="text/css">
			table.dataTable thead .sorting:after,
			table.dataTable thead .sorting:before,
			table.dataTable thead .sorting_asc:after,
			table.dataTable thead .sorting_asc:before,
			table.dataTable thead .sorting_asc_disabled:after,
			table.dataTable thead .sorting_asc_disabled:before,
			table.dataTable thead .sorting_desc:after,
			table.dataTable thead .sorting_desc:before,
			table.dataTable thead .sorting_desc_disabled:after,
			table.dataTable thead .sorting_desc_disabled:before {
				bottom: .5em;
			}
		</style>
		<h1>Global Template</h1>
		<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="th-sm">ID
					</th>
					<th class="th-sm">Name
					</th>
					<th class="th-sm">Prevew
					</th>
					<th class="th-sm">Tag
					</th>
					<th class="th-sm">Product
					</th>
					<th class="th-sm">Action
					</th>

				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($template_data as $key => $value) {
					$name = $value->folder;
					$name_temp = $value->name;
					$thumbnail = NBDESIGNER_DATA_URL . '/designs/' . $value->folder . '/preview/frame_0.png';
					// $thumbnail = NBDESIGNER_DATA_URL . 'designs/' . $value->folder . '/preview/frame_0.png';
					$link_edit_template = add_query_arg(array(
						'product_id'    => $value->product_id,
						'task'          => 'edit',
						'design_type'          => 'template',
						'nbd_item_key'          => $name,
						'rd'            => 'admin_templates',
						'id'      => $value->id
					), getUrlPageNBD('create'));

					if ($value->categories_id == '10000') {
						$tag = 'Global Template 1';
					}
					if ($value->categories_id == '10002') {
						$tag = 'Global Template 2';
					}
					if ($value->categories_id == '10003') {
						$tag = 'Global Template 3';
					}
					if ($value->categories_id == '10004') {
						$tag = 'Global Template 4';
					}
					if ($value->categories_id == '10005') {
						$tag = 'Global Template 5';
					}
					if ($value->categories_id == '10006') {
						$tag = 'Global Template 6';
					}
					if ($value->categories_id == '10007') {
						$tag = 'Global Template 7';
					}
					if ($value->categories_id == '10008') {
						$tag = 'Global Template 8';
					}
					if ($value->categories_id == '10009') {
						$tag = 'Global Template 9';
					}
					if ($value->categories_id == '100010') {
						$tag = 'Global Template 10';
					}
					if ($value->categories_id == '100011') {
						$tag = 'Global Template 11';
					}
					if ($value->categories_id == '100012') {
						$tag = 'Global Template 12';
					}
					if ($value->categories_id == '100013') {
						$tag = 'Global Template 13';
					}
					if ($value->categories_id == '100014') {
						$tag = 'Global Template 14';
					}
					if ($value->categories_id == '100015') {
						$tag = 'Global Template 15';
					}
					if ($value->categories_id == '100016') {
						$tag = 'Global Template 16';
					}
					if ($value->categories_id == '100017') {
						$tag = 'Global Template 17';
					}
					if ($value->categories_id == '100018') {
						$tag = 'Global Template 18';
					}
					if ($value->categories_id == '100019') {
						$tag = 'Global Template 19';
					}
					if ($value->categories_id == '100020') {
						$tag = 'Global Template 20';
					}
				?>
					<tr>
						<td><?= $value->id;  ?></td>
						<td><?= $name_temp;  ?></td>
						<td><img style="width:60px;height: 48px;border: 1px solid #b1adad;" class="nbd_column_folder_img" src="<?= $thumbnail; ?>"></td>
						<td><?= $tag; ?></td>
						<td><?php $product = wc_get_product($value->product_id);
							echo $product->get_name(); ?></td>
						<td>
							<div class="">
								<span class="edit">
									<a href="<?= $link_edit_template; ?>"><?php esc_html_e('Edit', 'woocommerce'); ?></a> |
								</span>
								<span class="delete">
									<a style="color: red;" class="delete" href="<?= esc_url(add_query_arg('delete', $value->id, '?page=nbdesigner_template')); ?>"><?php esc_html_e('Delete', 'woocommerce'); ?></a>
								</span>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="th-sm">ID
					</th>
					<th class="th-sm">Name
					</th>
					<th class="th-sm">Prevew
					</th>
					<th class="th-sm">Tag
					</th>
					<th class="th-sm">Product
					</th>
					<th class="th-sm">Action
					</th>
				</tr>
			</tfoot>
		</table>
		<script type="text/javascript">
			/* <![CDATA[ */
			jQuery('a.delete').click(function() {
				if (window.confirm('<?php esc_html_e('Are you sure you want to delete this Template?', 'woocommerce'); ?>')) {
					return true;
				}
				return false;
			});
			jQuery("#select_cate").val("<?php echo $seach; ?>");
			/* ]]> */
		</script>
	<?php
	}

	public static function addon_nbd_add_template_golal($actions, $tt, $REQUEST, $nonce, $item)
	{
		$url = esc_url(add_query_arg(array(
			'folder'          => $item['folder']
		), '?page=nbdesigner_manager_product&pid=' . $item['product_id'] . '&view=templates'));

		$actions['Global'] = '<a class="global" href="' . $url . '">' . esc_html__('Global', 'web-to-print-online-designer') . '</a>';
	?>
		<script type="text/javascript">
			/* <![CDATA[ */
			jQuery('a.global').click(function() {
				if (window.confirm('<?php esc_html_e('Are you sure you want to add this Template Global?', 'woocommerce'); ?>')) {
					return true;
				}
				return false;
			});

			/* ]]> */
		</script>
	<?php
		return $actions;
	}

	public static function addon_nbd_add_tmp_gol($link_manager_template)
	{
		global $wpdb;
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url = parse_url($actual_link, PHP_URL_QUERY);
		parse_str($url, $output);
		if ($output['folder']) {
			$product_id = $output['pid'];
			$folder = $output['folder'];
			$table_name = $wpdb->prefix . "templateall";
			$wpdb->insert($table_name, array(
				'product_id'    => $product_id,
				'folder'        => $folder,
			));
			header('Location: ' . $link_manager_template);
		}
	}

	public static function addon_add_thumail_categories_clipart()
	{
	?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php esc_html_e("Thumbnail categories clipart", 'web-to-print-online-designer'); ?> </th>
			<td class="forminp-text">
				<input type="file" name="thumailsvg[]" value="" accept=".svg,image/*" /><br />
			</td>
		</tr>
	<?php
	}

	public static function addon_save_thumail_categories($cat, $art_cat, $FILES, $tt)
	{
		if (isset($_FILES['thumailsvg'])) {
			$files = $_FILES['thumailsvg'];
			$path = NBDESIGNER_DATA_DIR . '/art_cat.json';
			foreach ($files['name'] as $key => $value) {
				$file = array(
					'name'     => $files['name'][$key],
					'type'     => $files['type'][$key],
					'tmp_name' => $files['tmp_name'][$key],
					'error'    => $files['error'][$key],
					'size'     => $files['size'][$key]
				);
				$uploaded_file_name = sanitize_file_name(basename($file['name']));
				$allowed_file_types = array('svg', 'png', 'jpg', 'jpeg');
				$art = $cat[$art_cat[0]];
				if (Nbdesigner_IO::checkFileType($uploaded_file_name, $allowed_file_types)) {
					$upload_overrides   = array('test_form' => false);
					$uploaded_file      = wp_handle_upload($file, $upload_overrides);
					if (isset($uploaded_file['url'])) {
						$new_path_art   = Nbdesigner_IO::create_file_path(NBDESIGNER_ART_DIR, $uploaded_file_name);
						$art->file    = $uploaded_file['file'];
						$art->url     = $uploaded_file['url'];
						if (!copy($art->file, $new_path_art['full_path'])) {
							$notice = apply_filters('nbdesigner_notices', nbd_custom_notices('error', esc_html__('Failed to copy.', 'web-to-print-online-designer')));
						} else {
							$art->file    = $new_path_art['date_path'];
							$art->url     = Nbdesigner_IO::wp_convert_path_to_url($new_path_art['full_path']);
						}
						if (isset($update) && $update) {
							$cat[$art_cat[0]] = $art;
							file_put_contents($path, json_encode($cat));
						} else {
							$cat[$art_cat[0]] = $art;
							file_put_contents($path, json_encode($cat));
						}
						$notice = apply_filters('nbdesigner_notices', nbd_custom_notices('success', esc_html__('Your art has been saved.', 'web-to-print-online-designer')));
					} else {
						$notice = apply_filters('nbdesigner_notices', nbd_custom_notices('error', sprintf(__('Error while upload art, please try again! <a target="_blank" href="%s">Force upload SVG</a>', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner&tab=general#nbdesigner_option_download_type')))));
					}
				} else {
					$notice = apply_filters('nbdesigner_notices', nbd_custom_notices('error', esc_html__('Incorrect file extensions.', 'web-to-print-online-designer')));
				}
			}
		}
	}

	public static function addon_change_notices($notice)
	{
		$notice = nbd_custom_notices('success', esc_html__('Your art has been saved.', 'web-to-print-online-designer'));
		return $notice;
	}

	public static function addon_thumail_categories_clipart($html)
	{
	?>
		<style type="text/css">
			/* Template tags wrap */
			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item {
				width: 33.33%;
				padding: 10px 13px;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .item-info {
				background-color: transparent;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item {
				border: none;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon {
				border-radius: 2px;
				border: none;
				width: 80px;
				height: 80px;
				position: relative;
				box-shadow: none !important;
			}

			.nbd-sidebar #tab-svg .result-loaded .content-items .item-img {
				width: 50%;
				box-sizing: border-box;
				display: inline-block;
				padding: 5px;
				cursor: pointer;
			}

			.template_shadow {
				position: absolute;
				left: 0;
				top: 0;
				z-index: 3;
				width: 100%;
				height: 100%;
				box-sizing: border-box;
				border: 2px solid #cdd3da;
				border-radius: 3px;
				background-color: #b8c1cc;
				box-shadow: 2px 2px 0 rgba(187, 187, 187, 0.5);
				transform: rotate(6deg);
				transform-origin: 0 150%;
				transition: all .15s linear;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon .template_shadow:nth-child(2) {
				z-index: 2;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon img {
				display: block;
				position: relative;
				left: 0;
				top: 0;
				z-index: 4;
				width: 100%;
				/*height: auto;*/
				transition: opacity .15s linear, transform .15s linear;
				box-sizing: border-box;
				border: 2px solid #fff;
				border-radius: 3px;
				box-shadow: 2px 2px 0 rgba(187, 187, 187, 0.5);
				transform: rotate(0deg);
				transform-origin: 40% 150%;
			}

			.nbd-sidebar #tab-svg .result-loaded .content-items .item-img:hover img {
				-webkit-box-shadow: 0 3px 5px -1px rgba(0, 0, 0, .2), 0 5px 8px 0 rgba(0, 0, 0, .14), 0 1px 14px 0 rgba(0, 0, 0, .12);
				-moz-box-shadow: 0 3px 5px -1px rgba(0, 0, 0, .2), 0 5px 8px 0 rgba(0, 0, 0, .14), 0 1px 14px 0 rgba(0, 0, 0, .12);
				box-shadow: 0 3px 5px -1px rgba(0, 0, 0, .2), 0 5px 8px 0 rgba(0, 0, 0, .14), 0 1px 14px 0 rgba(0, 0, 0, .12);
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon:hover .template_shadow {
				transform: rotate(2deg);
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon:hover .template_shadow:nth-child(2) {
				transform: rotate(10deg);
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .main-item .item-icon:hover img {
				transform: rotate(-6deg);
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items .item .item-info .item-name {
				margin-top: 5px;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items:after {
				display: table;
				clear: both;
				content: ' ';
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown.template-tags-wrap .main-items .items::before {
				display: table;
				content: " ";
			}

			.nbd-sidebar #tab-svg .result-loaded .content-items {
				text-align: left;
			}

			img.tag-thumb {
				max-width: 80px;
				height: 80px;
				background: #b8c1cc;
			}

			.nbd-sidebar #tab-svg .nbd-items-dropdown .main-items .items .item {
				position: relative !important;
			}
		</style>
		<div class="nbd-items-dropdown template-tags-wrap" style="width: 100%;">
			<div class="main-items">
				<div class="items" style="flex-wrap: wrap;display: flex;">
					<div class="item clipart-thumail clipart-{{index}}" attr-index="{{index}}" ng-repeat="(index,cat) in resource.clipart.data.cat" ng-click="changeCatthumail(index, cat);">
						<div class="main-item">
							<div class="item-icon">
								<span class="template_shadow"></span>
								<span class="template_shadow"></span>
								<img class="tag-thumb" ng-src="{{cat.url}}" />
							</div>
							<div class="item-info">
								<span class="item-name" ng-bind-html="cat.name | html_trusted"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="pointer"></div>
			</div>
			<div class="tab-main tab-scroll" style="display: none;">
				<hr>
				<div class="nbd-items-dropdown">
					<div>
						<div class="clipart-wrap">
							<div class="clipart-item" nbd-drag="art.url" extenal="false" type="svg" ng-repeat="art in resource.clipart.filteredArts | limitTo: resource.clipart.filter.currentPage * resource.clipart.filter.perPage" repeat-end="onEndRepeat('clipart')">
								<img ng-src="{{art.url}}" ng-click="addArt(art, true, true)" alt="{{art.name}}">
							</div>
						</div>
						<div class="loading-photo">
							<svg class="circular" viewBox="25 25 50 50">
								<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
							</svg>
						</div>
						<div class="tab-load-more" style="display: none;" ng-show="!resource.clipart.onload && resource.clipart.filteredArts.length && resource.clipart.filter.currentPage * resource.clipart.filter.perPage < resource.clipart.filter.total">
							<a class="nbd-button" ng-click="scrollLoadMore('#tab-svg', 'clipart')"><?php esc_html_e('Load more', 'web-to-print-online-designer'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	public static function custom_nbd_add_enable_check_spine()
	{
		$products = wc_get_products(array('status' => 'publish', 'limit' => -1));
		$selected = get_post_meta($_GET['post'], 'use_product_t', true) ? get_post_meta($_GET['post'], 'use_product_t', true) : '';
	?>
		<p class="nbo-form-field">
			<label for="_nbo_enable"><?php _e('Use template product', 'web-to-print-online-designer'); ?></label>
			<select name="use_product_t">
				<option <?php if ($selected == '') {
							echo 'selected';
						} ?> value="">---Choose product---</option>
				<?php foreach ($products as $key => $value) { ?>
					<option <?php if ($selected == $value->get_id()) {
								echo 'selected';
							} ?> value="<?php echo $value->get_id(); ?>"><?php echo $value->get_title(); ?></option>
				<?php } ?>
			</select>
		</p>
<?php
	}

	public static function add_save_price_upload($post_id)
	{

		if (isset($_POST['use_product_t'])) {
			update_post_meta($post_id, 'use_product_t', $_POST['use_product_t']);
		}
	}

	public static function custom_nbd_change_data_tem_by_id($template_data, $product_id, $variation_id)
	{
		$selected = get_post_meta($product_id, 'use_product_t', true) ? get_post_meta($product_id, 'use_product_t', true) : '';
		if ($selected != '') {
			$template_data  = nbd_get_resource_templates($selected, $variation_id, false, 0, true);
		}
		return $template_data;
	}
}

new TemplateForNBDesignerAdvanced();
