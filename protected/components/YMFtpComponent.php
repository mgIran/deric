<?php
/**
 * Created by YMGroup
 * Author: Yusef Mobasheri
 * Date: 4/16/2017
 * Time: 11:44 AM
 */
class YMFtpComponent extends CApplicationComponent
{
    /**
     * @var string the host for establishing FTP connection. Defaults to null.
     */
    public $host = null;

    /**
     * @var string the port for establishing FTP connection. Defaults to 21.
     */
    public $port = 21;

    /**
     * @var string the username for establishing FTP connection. Defaults to null.
     */
    public $username = null;

    /**
     * @var string the password for establishing FTP connection. Defaults to null.
     */
    public $password = null;

    /**
     * @var boolean
     */
    public $ssl = false;

    /**
     * @var string the timeout for establishing FTP connection. Defaults to 90.
     */
    public $timeout = 300;

    /**
     * @var boolean whether the ftp connection should be automatically established
     * the component is being initialized. Defaults to false. Note, this property is only
     * effective when the EFtpComponent object is used as an application component.
     */
    public $autoConnect = true;

    public $useFtpModels = array();

    private $_active = false;
    private $_errors = null;
    private $_connection = null;

    /**
     * @param    string $host
     * @param    string $username
     * @param    string $password
     * @param    boolean $ssl
     * @param    integer $port
     * @param    integer $timeout
     */
    public function __construct($host = null, $username = null, $password = null, $ssl = false, $port = 21, $timeout = 90)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->ssl = $ssl;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * Initializes the component.
     * This method is required by {@link IApplicationComponent} and is invoked by application
     * when the EFtpComponent is used as an application component.
     * If you override this method, make sure to call the parent implementation
     * so that the component can be marked as initialized.
     */
    public function init()
    {
        parent::init();
        if($this->autoConnect)
            $this->setActive(true);
    }

    /**
     * @return boolean whether the FTP connection is established
     */
    public function getActive()
    {
        return $this->_active;
    }

    /**
     * Open or close the FTP connection.
     * @param $value boolean whether to open or close FTP connection
     * @throws CException if connection fails
     */
    public function setActive($value)
    {
        if($value != $this->_active){
            if($value)
                $this->connect();
            else
                $this->close();
        }
    }

    /**
     * Connect to FTP if it is currently not
     * @throws CException if connection fails
     */
    public function connect()
    {
        if($this->_connection === null){
            // Connect - SSL?
            $this->_connection = $this->ssl?ftp_ssl_connect($this->host, $this->port, $this->timeout):ftp_connect($this->host, $this->port, $this->timeout);
            // Connection anonymous?
            if(!empty($this->username) AND !empty($this->password)){
                $login_result = ftp_login($this->_connection, $this->username, $this->password);
            }else{
                $login_result = true;
            }

            // Check connection
            if(!$this->_connection)
                throw new CException('FTP Library Error: Connection failed!');

            // Check login
            if((empty($this->username) AND empty($this->password)) AND !$login_result)
                throw new CException('FTP Library Error: Login failed!');
            $this->_active = true;
            ftp_pasv($this->_connection, true);
            set_time_limit(0);
            $this->rawCommand('OPTS UTF8 ON');
        }
    }

    /**
     * Closes the current FTP connection.
     *
     * @return bool
     * @throws CDbException
     */
    public function close()
    {
        if($this->getActive()){
            // Close the connection
            if(@ftp_close($this->_connection)){
                $this->_active = false;
                $this->_connection = null;
                $this->_errors = null;
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Passed an array of constants => values they will be set as FTP options.
     *
     * @param $config array
     * @return $this
     * @throws CDbException
     * @throws CException
     */
    public function setOptions($config)
    {
        if($this->getActive()){
            if(!is_array($config))
                throw new CException('EFtpComponent Error: The config parameter must be passed an array!');

            // Loop through configuration array
            foreach($config as $key => $value){
                // Set the options and test to see if they did so successfully - throw an exception if it failed
                if(!ftp_set_option($this->_connection, $key, $value))
                    throw new CException('EFtpComponent Error: The system failed to set the FTP option: "' . $key . '" with the value: "' . $value . '"');
            }

            return $this;
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Get executes a get command on the remote FTP server.
     *
     * @param $local string file
     * @param $remote string file
     * @param $mode int
     * @param bool $on_the_fly_mode
     * @return bool
     * @throws CDbException
     * @throws CHttpException
     */
    public function get($local, $remote, $mode = FTP_ASCII, $on_the_fly_mode = false)
    {
        if($this->getActive()){
            // Get the requested file
            if($on_the_fly_mode){
                $filename = explode('/', $remote);
                $count = count($filename);
                $ext = $filename[$count - 1];
                $ext = strtolower(substr(strrchr($ext, '.'), 1));
                switch($ext){
                    case 'pdf':
                        $mime = 'application/pdf';
                        break;
                    case 'zip':
                        $mime = 'application/zip';
                        break;
                    case 'jpeg':
                    case 'jpg':
                        $mime = 'image/jpg';
                        break;
                    case 'png':
                        $mime = 'image/png';
                        break;
                    default:
                        $mime = 'application/octet-stream';
                }
                header('Pragma: public');    // required
                header('Expires: 0');        // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $this->mdtm($remote)) . ' GMT');
                header('Cache-Control: private', false);
                header("Content-Type: {$mime}");
                header("Content-Disposition: attachment; filename= {$local}");
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . $this->size($remote));
                header("Content-Transfer-Encoding: binary");
                header("Connection: close");

                ftp_get($this->_connection, "php://output", $remote, FTP_BINARY);
                exit;
            }else{
                if(ftp_get($this->_connection, $local, $remote, $mode)){
                    // If successful, return the path to the downloaded file...
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * * Execute a remote command on the FTP server.
     *
     * @see        http://us2.php.net/manual/en/function.ftp-exec.php
     * @param $command string command
     * @return bool
     * @throws CDbException
     */
    public function execute($command)
    {
        if($this->getActive()){
            // Execute command
            if(ftp_exec($this->_connection, $command)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * * Execute a remote command on the FTP server.
     *
     * @see        http://us2.php.net/manual/en/function.ftp-exec.php
     * @param $command string command
     * @return bool
     * @throws CDbException
     */
    public function rawCommand($command)
    {
        if($this->getActive()){
            // Execute command
            if(ftp_raw($this->_connection, $command)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Put executes a put command on the remote FTP server.
     *
     * @param $remote string file
     * @param $local string file
     * @param int $mode
     * @return bool
     * @throws CDbException
     */
    public function put($remote, $local, $mode = FTP_BINARY)
    {
        if($this->getActive()){
            // Upload the local file to the remote location specified
            if(ftp_put($this->_connection, $remote, $local, $mode)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Rename executes a rename command on the remote FTP server.
     *
     * @param $old string filename
     * @param $new string filename
     * @return bool
     * @throws CDbException
     */
    public function rename($old, $new)
    {
        if($this->getActive()){
            // Rename the file
            if(ftp_rename($this->_connection, $old, $new)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Rmdir executes an rmdir (remove directory) command on the remote FTP server.
     *
     * @param $dir string directory
     * @return bool
     * @throws CDbException
     */
    public function rmdir($dir)
    {
        if($this->getActive()){
            if(!empty($dir)){
                # here we attempt to delete the file/directory
                if(!(@ftp_rmdir($this->_connection, $dir) || @ftp_delete($this->_connection, $dir))){
                    # if the attempt to delete fails, get the file listing
                    $fileList = @ftp_nlist($this->_connection, $dir);
                    # loop through the file list and recursively delete the FILE in the list
                    foreach($fileList as $file){
                        $this->rmdir($dir . '/' . $file);
                    }
                    #if the file list is empty, delete the DIRECTORY we passed
                    $this->rmdir($dir);
                }
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Mkdir executes an mkdir (create directory) command on the remote FTP server.
     *
     * @param $dir string directory
     * @return bool
     * @throws CDbException
     */
    public function mkdir($dir)
    {
        if($this->getActive()){
            // create directory
            if(@ftp_mkdir($this->_connection, $dir)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Mkdir executes an mkdir (create directory) command on the remote FTP server.
     *
     * @param $dir string directory
     * @return bool
     * @throws CDbException
     */
    public function isDir($dir)
    {
        if($this->getActive()){
            // create directory
            if($this->size($dir) === false){
                return @$this->listFiles($dir)?true:false;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    public function showFile($file)
    {
        if($this->getActive()){
            // create directory
            $prefix = $this->ssl?'ftps':'ftp';
            return "{$prefix}://{$this->username}:{$this->password}@{$this->host}/{$file}";

        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Returns the last modified time of the given file
     * Note: Not all servers support this feature!
     * Note: mdtm method does not work with directories.
     *
     * @param $file string filename
     * @return bool|int
     * @throws CDbException
     */
    public function mdtm($file)
    {
        if($this->getActive()){
            // get the last modified time
            $buff = ftp_mdtm($this->_connection, $file);
            if($buff != -1){
                return $buff;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Returns the size of the given file
     * Note: Not all servers support this feature!
     *
     * @param $file string filename
     * @return bool|int
     * @throws CDbException
     */
    public function size($file)
    {
        if($this->getActive()){
            // get the size of $file
            $buff = ftp_size($this->_connection, $file);
            if($buff != -1){
                return $buff;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Remove executes a delete command on the remote FTP server.
     *
     * @param $file string filename
     * @return bool
     * @throws CDbException
     */
    public function delete($file)
    {
        if($this->getActive()){
            // Delete the specified file
            if(ftp_delete($this->_connection, $file)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Change the current working directory on the remote FTP server.
     *
     * @param $dir string directory
     * @return bool
     * @throws CDbException
     */
    public function chdir($dir)
    {
        if($this->getActive()){
            // Change directory
            if(ftp_chdir($this->_connection, $dir)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Changes to the parent directory on the remote FTP server.
     *
     * @return bool
     * @throws CDbException
     */
    public function parentDir()
    {
        if($this->getActive()){
            // Move up!
            if(ftp_cdup($this->_connection)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Returns the name of the current working directory.
     *
     * @return string
     * @throws CDbException
     */
    public function currentDir()
    {
        if($this->getActive()){
            return ftp_pwd($this->_connection);
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Permissions executes a chmod command on the remote FTP server.
     *
     * @param $file string filename
     * @param $mode
     * @return bool
     * @throws CDbException
     */
    public function chmod($file, $mode)
    {
        if($this->getActive()){
            // Change the desired file's permissions
            if(ftp_chmod($this->_connection, $mode, $file)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * ListFiles executes a nlist command on the remote FTP server, returns an array of file names, false on failure.
     *
     * @param $directory string directory
     * @return array
     * @throws CDbException
     */
    public function listFiles($directory)
    {
        if($this->getActive()){
            return ftp_nlist($this->_connection, $directory);
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * ListFiles executes a rawlist command on the remote FTP server, returns an array of file names, false on failure.
     *
     * @param $directory string directory
     * @return array
     * @throws CDbException
     */
    public function rawList($directory)
    {
        if($this->getActive()){
            return ftp_rawlist($this->_connection, $directory);
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Checks whether a file or directory exists in ftp server
     *
     * @param $path
     * @return bool
     * @throws CDbException
     */
    public function fileExists($path)
    {
        $filename = explode('/', $path);
        $count = count($filename);
        $filename = $filename[$count - 1];
        $directory = str_replace($filename, '', $path);
        if(empty($directory)){
            $directory = '/';
            $path = $directory . $path;
        }
        if($this->getActive()){

            $contents_on_server = ftp_nlist($this->_connection, $directory);
            if($contents_on_server && in_array($path, $contents_on_server)){
                return true;
            }else{
                return false;
            }
        }else{
            throw new CDbException('EFtpComponent is inactive and cannot perform any FTP operations.');
        }
    }

    /**
     * Close the FTP connection if the object is destroyed.
     *
     * @return    boolean
     */
    public function __destruct()
    {
        return @$this->close();
    }

    /**
     * Checks ftp mode is true for this controller
     *
     * @param $modelName
     * @return bool
     */
    public function checkFtpMode($modelName)
    {
        $useFtpModels = Yii::app()->ftp->useFtpModels;
        if(key_exists($modelName, $useFtpModels) && $useFtpModels[$modelName] === true)
            return true;
        else
            return false;
    }

    public function read_chunk_file($file, $retbytes = true)
    {
        $chunksize = 100 * 1024;
        $buffer = '';
        $cnt = 0;
        $handle = @fopen($file, 'rb');
        if($size = @filesize($file)){
            header("Content-Length: " . $size);
        }
//        $chunksize = $size;
        if(false === $handle){

            return false;
        }
        while(!@feof($handle)){
            $buffer = @fread($handle, $chunksize);
            echo $buffer;
            if($retbytes){
                $cnt += strlen($buffer);
            }
        }

        $status = @fclose($handle);

        if($retbytes && $status){
            return $cnt;
        }

        return $status;
        $data_file = $file;
        $data_size = @filesize($data_file);

        if(isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])){
            $ranges_str = (isset($_SERVER['HTTP_RANGE']))?$_SERVER['HTTP_RANGE']:$HTTP_SERVER_VARS['HTTP_RANGE'];
            $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));
            //Now its time to check the ranges
            $ranges_arr[0] = intval($ranges_arr[0]);
            if((intval($ranges_arr[0]) >= intval($ranges_arr[1]) &&
                    $ranges_arr[1] != "" &&
                    $ranges_arr[0] != "") ||
                ($ranges_arr[1] == "" && $ranges_arr[0] == "")
            ){
                //Just serve the file normally request is not valid :(
                $ranges_arr[0] = 0;
                $ranges_arr[1] = $data_size;
            }
        }else{ //The client dose not request HTTP_RANGE so just use the entire file
            $ranges_arr[0] = 0;
            $ranges_arr[1] = $data_size;
        }

        //Now its time to serve file
        $file = fopen($data_file, 'rb');

        //I use seek and tell to find the location, since I\'m too lazy now
        //You may use some + or - instead of all this :)
        if($ranges_arr[0] == ""){
            //Status 1 : the first one dose not exist
            fseek($file, -intval($ranges_arr[1]), SEEK_END);
            $seek_start = ftell($file);
            fseek($file, intval($ranges_arr[1]), SEEK_CUR);
            $seek_end = ftell($file);
        }elseif($ranges_arr[1] == ""){
            //Status 2 : the last one dose not exist
            fseek($file, intval($ranges_arr[0]), SEEK_SET);
            $seek_start = ftell($file);
            fseek($file, $data_size - intval($ranges_arr[1]), SEEK_CUR);
            $seek_end = ftell($file);
        }else{
            //Status 3 : Both are here :)
            fseek($file, intval($ranges_arr[0]), SEEK_SET);
            $seek_start = ftell($file);
            fseek($file, intval($ranges_arr[1]) - intval($ranges_arr[0]), SEEK_CUR);
            $seek_end = ftell($file);
        }

        //Lets send headers

        header('HTTP/1.0 206 Partial Content');
        header('Status: 206 Partial Content');
        header('Accept-Ranges: bytes');

        header("Content-Range: bytes $seek_start-$seek_end/$data_size");
        header("Content-Length: " . ($seek_end - $seek_start));

        //Finally serve data and done ~!
        $data_len = $seek_end - $seek_start;
        fseek($file, $seek_start, SEEK_SET);
        $bufsize = 2048;

        ignore_user_abort(true);
        @set_time_limit(0);
        while(!(connection_aborted() || connection_status() == 1) && $data_len > 0){
            if($data_len < $bufsize)
                echo fread($file, $data_len);
            else
                echo fread($file, $bufsize);
            $data_len -= $bufsize;
            flush();

        }

        fclose($file);
        return 1;
    }
}