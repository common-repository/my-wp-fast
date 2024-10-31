<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<div class="wrap">
  <h2>My WP Fast</h2>
<?php if(!$this->pro){ ?>
<div class="notice updated my-acf-notice is-dismissible" >
        <p><strong>Important</strong> My WP Fast it's it's free for small websites that have less then <strong>250 visits per day.</strong></p>
        <p>To purchase a license for your websites <a href="https://mywpfast.com" target="">click here.</a></p>
</div>
<?php } ?>
<div class="container">

<div class="button__group">
    <a class="btn active" data-tab="config">Configuration</a>
    <a class="btn" data-tab="ignore">Ignore Files</a>
    <a class="btn" data-tab="clear_cache">Clear Cache</a>
    <a class="btn" data-tab="license">CDN</a>
  </div>

<div class="whitebox config" style="display: block">
  <div class="half">
<form method="post" action="?page=make-wp-faster/config.php<?php echo '&nonce=' . wp_create_nonce('check_nonce'); ?>">
  <div class="box-options">
    <div class="option">
      <div class="sb-toggle">
        <input <?php checked($this->debug, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="debug" name="debug">
         <label class="sb-toggle__label sb-toggle__label--red" for="debug"></label>
         <strong>Debug</strong>
          <p class="description">With debug ON you can use to see what files are being minimized and merged. It's useful if website is not working properly and you want to ignore files.</p>
      </div>
    </div>
</div>

  <div class="box-options">
    <div class="option">
    <div class="sb-toggle">
      <input <?php checked($this->minimize_css, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="minimize_css" name="minimize_css">
      <label class="sb-toggle__label sb-toggle__label--green" for="minimize_css"></label>
      <strong>Compile CSS</strong>
      <p class="description">Minimize and merge CSS files in one.</p>
    </div>
    </div>
</div>

<div class="box-options">
    <div class="option">
<div class="sb-toggle">
  <input <?php checked($this->minimize_js, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="minimize_js" name="minimize_js">
  <label class="sb-toggle__label sb-toggle__label--green" for="minimize_js"></label>
  <strong>Compile JS</strong>
  <p class="description">Minimize and merge JS files in one.</p>
</div>
</div>
</div>

<div class="box-options">
    <div class="option">
<div class="sb-toggle">
  <input <?php checked($this->minimize_html, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="minimize_html" name="minimize_html">
  <label class="sb-toggle__label sb-toggle__label--green" for="minimize_html"></label>
  <strong>Minimize HTML</strong>
  <p class="description">Minimize HTML output of your website.</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->lazy_load, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="lazy_load" name="lazy_load">
  <label class="sb-toggle__label sb-toggle__label--green" for="lazy_load"></label>
  <strong>Lazy Load</strong>
  <p class="description">Lazy Load all images of your website</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->javascript_defer, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="javascript_defer" name="javascript_defer">
  <label class="sb-toggle__label sb-toggle__label--blue" for="javascript_defer"></label>
  <strong>Add Defer to Javascript</strong>
  <p class="description">Make your website faster by adding defer attribute on your javascript files.</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->remove_head_trash, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="remove_head_trash" name="remove_head_trash">
  <label class="sb-toggle__label sb-toggle__label--blue" for="remove_head_trash"></label>
  <strong>Remove Wordpress Head trash</strong>
  <p class="description">Remove some trash that Wordpress add in head of your website.</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->remove_files_version, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="remove_files_version" name="remove_files_version">
  <label class="sb-toggle__label sb-toggle__label--blue" for="remove_files_version"></label>
  <strong>Remove Files Version</strong>
  <p class="description">Remove file versions can help browsers to cache your website files.</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->remove_emojis, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="remove_emojis" name="remove_emojis">
  <label class="sb-toggle__label sb-toggle__label--blue" for="remove_emojis"></label>
  <strong>Remove WP Emojis</strong>
    <p class="description">Remove Wordpress Emoji Files</p>
</div>
</div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->add_expires_header, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="add_expires_header" name="add_expires_header">
  <label class="sb-toggle__label sb-toggle__label--blue" for="add_expires_header"></label>
  <strong>Apply Expires Header</strong>
    <p class="description">Apply Expires Header to your files</p>
</div>
</div>
</div>

<div class="box-options">
  <div class="option">
    <div class="sb-toggle">
      <input <?php checked($this->gzip, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="gzip" name="gzip">
      <label class="sb-toggle__label sb-toggle__label--blue" for="gzip"></label>
      <strong>Activate Gzip</strong>
        <p class="description">Activate Gzip compression if your website gets white please edit .htaccess file and delete #BEGIN my_wp_fast_gzip to #END my_wp_fast_gzip</p>
    </div>
  </div>
</div>

<div class="box-options">
<div class="option">
<div class="sb-toggle">
  <input <?php checked($this->force_ssl, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="force_ssl" name="force_ssl">
  <label class="sb-toggle__label sb-toggle__label--blue" for="force_ssl"></label>
  <strong>Force HTTPS</strong>
    <p class="description">Force website to use HTTPS</p>
</div>
</div>
</div>

<div class="clearfix"></div>
<div class="button__group">
<input type="submit" class="btn" value="Save Configuration" name="save_configuration">
</div>
</form>
</div>
<div class="half">
  <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/loading.png' ?>">
</div>
</div>

<div class="whitebox ignore">
  <div class="half">
<form method="post" action="?page=make-wp-faster/config.php<?php echo '&nonce=' . wp_create_nonce('check_nonce'); ?>">
      <?php if(count($this->getAllFiles()) == 0){ ?>
        <p class="description">Before you can ignore files you must activate the minify css or js option and then visit your website. After that you will be able to see the list of css and js files your website have.</p>
      <?php } ?>
      <table class="table">
        <tr>
            <td>File</td>
            <td style="text-align: center; font-weight: 400">Ignore</td>
        </tr>
        <?php foreach ($this->getAllFiles() as $file) {?>
          <tr>
            <td><div class="tag selected <?php echo $file->type ?>"><?php echo $file->type ?></div> <strong><?php echo $file->config ?></strong>
                <br><small><?php echo str_replace(get_site_url(), '', $file->value) ?></small>
            </td>
            <td>
              <div class="sb-toggle">
                  <input type='hidden' value='0' name="<?php echo $file->config ?>::<?php echo $file->type ?>">
                  <input <?php checked($this->isIgnored($file->config, $file->type), true, true);?> class="sb-toggle__checkbox" type="checkbox" id="<?php echo $file->config ?>::<?php echo $file->type ?>" name="<?php echo $file->config ?>::<?php echo $file->type ?>">
                  <label class="sb-toggle__label sb-toggle__label--red" for="<?php echo $file->config ?>::<?php echo $file->type ?>"></label>
                </div>
            </td>
          </tr>
        <?php }?>
      </table>
      <div class="button__group">
      <input type="submit" class="btn" value="Save Configuration" name="save_ignores">
    </div>
</form>
</div>
<div class="half">
  <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/files.png' ?>">
</div>
</div>

  <div class="whitebox clear_cache">
    <div class="half">
      <p class="description">You can clear all cache files of your website by clicking the button bellow.</p>
      <div class="button__group">
       <a href="?page=make-wp-faster/config.php&delete_all=trueaction<?php echo '&nonce=' . wp_create_nonce('check_nonce'); ?>" class="btn">Clear All Cache Files</a>
     </div>
    </div>
    <div class="half">
      <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/clear.png' ?>">
    </div>
   
  </div>

  <div class="whitebox license">
    <div class="half">
      <form method="post" action="?page=make-wp-faster/config.php<?php echo '&nonce=' . wp_create_nonce('check_nonce'); ?>">
        <div class="box-options">
        <div class="option">
        <div class="sb-toggle">
          <input type='hidden' value="0" name="cdn">
          <input <?php checked($this->cdn, true, true);?> class="sb-toggle__checkbox" type="checkbox" id="cdn" name="cdn">
          <label class="sb-toggle__label sb-toggle__label--green" for="cdn"></label>
          <strong>CDN</strong>
          <p class="description">Use a CDN to delivery your static files can make your website much faster.</p>
        </div>
        </div>
        </div>
        <div>
          <strong>CDN URL</strong>
          <input type="text" class="text input" name="cdn_url" value="<?php echo $this->cdn_url ?>" />
          <p class="description">The URL your CDN Example: https://cdn.example.com</p>
        </div>
        <div>
          <strong>ORIGIN URL</strong>
          <input type="text" class="text input" name="origin_url" value="<?php echo $this->origin_url ?>" />
          <p class="description">The URL of your website: https://mysite.com</p>
        </div>
         <div class="button__group">
          <input type="submit" class="active btn" value="Save Configuration" name="save_cdn">
        </div>
      </form>
    </div>
    <div class="half">
        <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/space.png' ?>">
      </div>
  </div>
 </div>