<?php

namespace ThemeHouse\UIX\Util;

/**
 * Class Ftp
 * @package ThemeHouse\UIX\Util
 */
class Ftp
{
    /**
     * @var resource
     */
    protected $ftp;
    /**
     * @var
     */
    protected $ftpHost;
    /**
     * @var
     */
    protected $ftpPort;
    /**
     * @var
     */
    protected $ftpUser;
    /**
     * @var
     */
    protected $ftpPass;
    /**
     * @var string
     */
    protected $ftpPath;

    /**
     * Ftp constructor.
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $directory
     * @throws \Exception
     */
    public function __construct($host, $port, $username, $password, $directory)
    {
        if (substr($directory, -1) !== DIRECTORY_SEPARATOR) {
            $directory = $directory . DIRECTORY_SEPARATOR;
        }
        $this->ftpHost = $host;
        $this->ftpPort = $port;
        $this->ftpUser = $username;
        $this->ftpPass = $password;
        $this->ftpPath = $directory;

        $this->ftp = ftp_connect($host, $port);
        if (!$this->ftp) {
            throw new \Exception('Unable to connect to FTP server ' . $host . ':' . $port);
        }

        try {
            ftp_login($this->ftp, $username, $password);
        } catch (\Exception $e) {
            throw new \Exception('Unable to login to FTP server with username ' . $username . ' and entered password');
        }

        ftp_chdir($this->ftp, $directory);
        $list = ftp_nlist($this->ftp, $directory);
        if (!in_array('index.php', $list) ||
            !in_array('proxy.php', $list) ||
            !in_array('styles', $list) ||
            !in_array('js', $list)) {
            throw new \Exception('The provided directory does not appear to be a valid XenForo directory');
        }
    }

    /**
     * @param $source
     * @param $destination
     * @return bool
     */
    public function move($source, $destination)
    {
        $source = str_ireplace(\XF::getRootDirectory(), '', $source);
        $destination = str_ireplace($this->ftpPath, '', $destination);

        $source = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);

        $firstCharSource = substr($source, 0, 1);
        $firstCharDest = substr($destination, 0, 1);

        if ($firstCharSource != DIRECTORY_SEPARATOR) {
            $source = DIRECTORY_SEPARATOR . $source;
        }
        if ($firstCharDest != DIRECTORY_SEPARATOR) {
            $destination = DIRECTORY_SEPARATOR . $destination;
        }

        $source = \XF::getRootDirectory() . $source;
        $destination = $this->ftpPath . $destination;

        $source = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);

        return ftp_put($this->ftp, $destination, $source, FTP_BINARY);
    }

    /**
     * @param $dirName
     * @return bool|string
     */
    public function mkdir($dirName)
    {
        $options = \XF::options();
        $curDir = ftp_pwd($this->ftp);

        if (!@ftp_chdir($dirName, $this->ftp)) {
            $dirName = str_replace(\XF::getRootDirectory(), $this->ftpPath, $dirName);
            if ($dirName == $this->ftpPath) {
                return true;
            }

            $dirName = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $dirName);

            $pwd = ftp_pwd($this->ftp);
            if (@ftp_chdir($this->ftp, $dirName)) {
                ftp_chdir($this->ftp, $pwd);
            } else {
                $return = ftp_mkdir($this->ftp, $dirName);
                ftp_chmod($this->ftp, $options->th_newDirectoryPermissions_uix, $dirName);

                return $return;
            }
        } else {
            @ftp_chdir($this->ftp, $curDir);
        }

        return null;
    }
}
