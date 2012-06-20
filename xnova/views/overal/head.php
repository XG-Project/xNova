<?php echo doctype('html5'); ?>
<html lang="<?php echo current_lang(); ?>">
<head>
	<title><?php echo $this->config->item('game_name'); ?></title>
	<link rel="shortcut icon" href="<?php echo site_url("skins/".skin()."/images/favicon.ico"); ?>" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta charset="UTF-8" />
	<meta name="description" content="<?php echo config_item('description'); ?>" />
	<meta name="keywords" content="<?php echo config_item('keywords'); ?>" />
	<?php if (config_item('debug')) : ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("css/profiler.css"); ?>" />
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("skins/".skin()."/css/overal.css"); ?>" />
	<?php if (defined('INGAME')) : ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("skins/".skin()."/css/ingame.css"); ?>" />
	<?php else : ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("skins/".skin()."/css/public.css"); ?>" />
	<?php endif; ?>
	<link href="<?php echo base_url(); ?>" title="<?php echo config_item('game_name'); ?>" rel="index" />
	<link rel="canonical" href="<?php echo base_url(); ?>" />
	<?php echo $skin; ?>
</head>
<body <?php if ( ! config_item('debug')) : ?>style="overflow: hidden;" <?php endif; ?>>