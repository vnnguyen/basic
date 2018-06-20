<?php
namespace app\models;
use yii;
use common\models\File;
use yii\helpers\Url;
class Myupload extends UploadHandler
{

	public $ul_dir = '';
    public $l_time = 10000000;
	public function __construct($options = null, $initialize = true, $error_messages = null) {
        $this->response = array();
        $script_url = $this->get_full_url().'/'.$this->basename($this->get_server_var('SCRIPT_NAME'));
        $upload_dir = $this->ul_dir != '' ? dirname($this->get_server_var('SCRIPT_FILENAME')). '/'. $this->ul_dir : dirname($this->get_server_var('SCRIPT_FILENAME')).'/files/';
        $upload_url = $this->ul_dir != '' ? $this->get_full_url().'/'.$this->ul_dir : $this->get_full_url().'/files/';
        $this->options = array(
            'script_url' => $script_url,
            'upload_dir' => $upload_dir,
            'upload_url' => $upload_url,
            'input_stream' => 'php://input',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // By default, allow redirects to the referer protocol+host:
            'redirect_allow_target' => '/^'.preg_quote(
              parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_SCHEME)
                .'://'
                .parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_HOST)
                .'/', // Trailing slash to not match subdomains by mistake
              '/' // preg_quote delimiter param
            ).'/',
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Use exif_imagetype on all files to correct file extensions:
            'correct_image_extensions' => false,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => array(
                // The empty image version key defines options for the original image:
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                // Uncomment the following to create medium sized images:
                /*
                'medium' => array(
                    'max_width' => 800,
                    'max_height' => 600
                ),
                */
                'thumbnail' => array(
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
                    'max_width' => 80,
                    'max_height' => 80
                )
            ),
            'print_response' => true
        );
        if ($options) {
            $this->options = $options + $this->options;
        }
        if ($error_messages) {
            $this->error_messages = $error_messages + $this->error_messages;
        }
        // if ($upload_url) {
        //     $upload_url = str_replace(Yii::getAlias('@www'), Yii::getAlias('@webroot'), $upload_url);
        //     if (is_dir($upload_url)) {
        //         $this->recursiveRemove($upload_url, 5*3600);
        //     }
        // }
        if ($initialize) {
            $this->initialize();
        }
        
    }
    protected function initialize() {
        switch ($this->get_server_var('REQUEST_METHOD')) {
            case 'OPTIONS':
            case 'HEAD':
                $this->head();
                break;
            case 'GET':
                $this->get($this->options['print_response']);
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                $this->post($this->options['print_response']);
                break;
            case 'DELETE':
                $this->delete($this->options['print_response'], isset($_GET['id'])?$_GET['id']:0);
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');
        }
    }
    protected function get_user_id() {
        @session_start();
        return Yii::$app->user->id.'/'.session_id();
    }
    protected function set_additional_file_properties($file) {
        $file->deleteUrl = Yii::$app->request->hostInfo
        	.Url::to([Yii::$app->request->url, $this->get_singular_param_name() => rawurlencode($file->name)]);
        $file->deleteType = $this->options['delete_type'];
        if ($file->deleteType !== 'DELETE') {
            $file->deleteUrl .= '&_method=DELETE';
        }
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
    }

    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? ((int)$matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return '_'.$index.''.$ext;
    }

    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?:\_([\d]+))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }
    private $cntF = 0;
    public function delete($print_response = true, $id = 0) {
        $response = [];
        $file_names = $this->get_file_names_params();
        if (empty($file_names)) {
            $file_names = array($this->get_file_name_param());
        }

        if ($id > 0) {
            foreach ($file_names as $file_name) {
                $file = File::findOne($id);
                if ($file == null) {
                    return json_encode(['error' => 'file not found']);
                }
                $file_path = $file['url'];
                $file_path = str_replace(Yii::getAlias('@www'), Yii::getAlias('@webroot'), $file_path);
                $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
                $response[$file_name] = $success;
            }
        } else {
            foreach ($file_names as $file_name) {
                $file_path = $this->get_upload_path($file_name);
                $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
                if ($success) {
                    foreach ($this->options['image_versions'] as $version => $options) {
                        if (!empty($version)) {
                            $file = $this->get_upload_path($file_name, $version);
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                    }
                }
                $response[$file_name] = $success;
            }
        }

        return $this->generate_response($response, $print_response);
    }
    public function recursiveRemove($dir_path, $ltime = 3600)
    {
        $structure = glob(rtrim($dir_path, "/").'/*');
        if (is_array($structure)) {
            foreach($structure as $file) {
                if (is_dir($file)) {
                    if (!is_readable($file)) continue;
                    if (count(scandir($file)) <= 2) {
                        $dirlastmod = filemtime($file);
                        if((time() - $dirlastmod) > $ltime) {
                            rmdir($file);
                        }
                    } else {
                        $this->recursiveRemove($file, $ltime);
                    }
                } elseif (is_file($file)) {
                    if ($file == "{$file}.part") {
                        continue;
                    }
                    $lastmod = filemtime($file);
                    if((time() - $lastmod) > $ltime) {
                        unlink($file);
                    }
                }
            }

        }
        // reset($structure);
        while ($this->is_dir_empty($dir_path)) {
            $dirlastmod = filemtime($dir_path);
            if((time() - $dirlastmod) > $ltime) {
                rmdir($dir_path);
            }
        }
    }
    function is_dir_empty($dir_path) {
        if (!is_readable($dir_path)) return NULL;
        return (count(scandir($dir_path)) <= 2);
    }
}