<?php


class ClamAVScannerLOcal
{
    private string $host;
    private int $port;
    private int $timeout;

    public function __construct(
        string $host = 'clamav',
        int $port = 3310,
        int $timeout = 30
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * Scan a file with ClamAV (STREAM command)
     *
     * @throws \RuntimeException when virus found or scan fails
     */
    public function scanFile(string $filePath): void
    {
        if (!is_file($filePath)) {
            throw new \RuntimeException('File not found for virus scan');
        }

        $fp = fsockopen(
            $this->host,
            $this->port,
            $errno,
            $errstr,
            $this->timeout
        );

        if (!$fp) {
            throw new \RuntimeException("ClamAV connection failed: $errstr");
        }

        // Tell clamd we will stream data
        fwrite($fp, "zINSTREAM\0");

        $fh = fopen($filePath, 'rb');
        while (!feof($fh)) {
            $chunk = fread($fh, 8192);
            fwrite($fp, pack('N', strlen($chunk)) . $chunk);
        }

        // End of stream
        fwrite($fp, pack('N', 0));
        fclose($fh);

        $response = fgets($fp);
        fclose($fp);

        if ($response === false) {
            throw new \RuntimeException('No response from ClamAV');
        }

        // Clean
        if (strpos($response, 'OK') !== false) {
            return;
        }

        // Virus found
        if (strpos($response, 'FOUND') !== false) {
            throw new \RuntimeException('Virus detected: ' . trim($response));
        }

        // Any other error
        throw new \RuntimeException('ClamAV scan error: ' . trim($response));
    }
}



 /**
     * @param array $file $_FILES['xxx']
     * @throws RuntimeException
     */
    private function scanImageWithClamAV(array $file): void
    {
        if (
            !isset($file['tmp_name']) ||
            $file['tmp_name'] === '' ||
            !is_uploaded_file($file['tmp_name'])
        ) {
            throw new \RuntimeException('Invalid upload file.');
        }

        $scanner = new ClamAVScanner(
            'clamav', // docker service name OR host
            3310
        );
        
        try {
            // $scanner->scanFile($file['tmp_name']);
            // $scanner->scanFile($file['tmp_name']);
        $file = '/tmp/eicar.txt';

        file_put_contents(
            $file,
            'X5O!P%@AP[4\\PZX54(P^)7CC)7}$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!$H+H*'
        );

        $scanner->scanFile($file);
        } catch (\RuntimeException $e) {
            // Block upload immediately
            SC_Utils_Ex::sfDispSiteError(
                FREE_ERROR_MSG,
                '',
                false,
                $e->getMessage()
            );
        }

    }


    


    $key = $this->arrForm['image_key'];
                        $this->scanImageWithClamAV($_FILES[$key]);