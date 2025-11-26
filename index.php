<?php
/**
 *
 * Copyright (C) 2012  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * File Name : index.php
 */

define('INDEX_AUTH', 1);

require 'sysconfig.inc.php';

// Force HTTPS if enabled
if ($sysconf['https']['enable'] && empty($_SERVER['HTTPS']) && (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// set default vars
$page_title = 'Nayanes: The SLiMS Search Proxy';
$main_content = '';

// start the output buffering for main content
ob_start();
if (isset($_GET['p'])) {
  $path = trim(strip_tags($_GET['p']));
  // some extra checking - modified to allow https
  $path = preg_replace('@^(ftp|sftp|file|smb):@i', '', $path);
  // Allow http and https but with additional security checks
  if (preg_match('@^(http|https)://@i', $path)) {
      // Validate URL for security
      $parsed_url = parse_url($path);
      $allowed_domains = array($sysconf['domain']); // Add your allowed domains
      if (!in_array($parsed_url['host'], $allowed_domains)) {
          // If not from allowed domain, treat as local path
          $path = preg_replace('@^(http|https)://@i', '', $path);
      }
  }
  $path = preg_replace('@\/@i','',$path);
  // check if the file exists
  if (file_exists(LIB_DIR.'contents/'.$path.'.inc.php')) {
    include LIB_DIR.'contents/'.$path.'.inc.php';
  } else {
    include LIB_DIR.'contents/default.inc.php';
  }
} else {
  include LIB_DIR.'contents/default.inc.php';
}
// main content grab
$main_content = ob_get_clean();

require 'templates/'.$sysconf['theme'].'/index_template.inc.php';
