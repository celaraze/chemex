<?php

namespace Box\Spout\Common\Helper;

/**
 * Class GlobalFunctionsHelper
 * This class wraps global functions to facilitate testing
 *
 * @codeCoverageIgnore
 */
class GlobalFunctionsHelper
{
    /**
     * Wrapper around global function fopen()
     * @param string $fileName
     * @param string $mode
     * @return resource|bool
     * @see fopen()
     *
     */
    public function fopen($fileName, $mode)
    {
        return \fopen($fileName, $mode);
    }

    /**
     * Wrapper around global function fgets()
     * @param resource $handle
     * @param int|null $length
     * @return string
     * @see fgets()
     *
     */
    public function fgets($handle, $length = null)
    {
        return \fgets($handle, $length);
    }

    /**
     * Wrapper around global function fputs()
     * @param resource $handle
     * @param string $string
     * @return int
     * @see fputs()
     *
     */
    public function fputs($handle, $string)
    {
        return \fputs($handle, $string);
    }

    /**
     * Wrapper around global function fflush()
     * @param resource $handle
     * @return bool
     * @see fflush()
     *
     */
    public function fflush($handle)
    {
        return \fflush($handle);
    }

    /**
     * Wrapper around global function fseek()
     * @param resource $handle
     * @param int $offset
     * @return int
     * @see fseek()
     *
     */
    public function fseek($handle, $offset)
    {
        return \fseek($handle, $offset);
    }

    /**
     * Wrapper around global function fgetcsv()
     * @param resource $handle
     * @param int|null $length
     * @param string|null $delimiter
     * @param string|null $enclosure
     * @return array
     * @see fgetcsv()
     *
     */
    public function fgetcsv($handle, $length = null, $delimiter = null, $enclosure = null)
    {
        // PHP uses '\' as the default escape character. This is not RFC-4180 compliant...
        // To fix that, simply disable the escape character.
        // @see https://bugs.php.net/bug.php?id=43225
        // @see http://tools.ietf.org/html/rfc4180
        $escapeCharacter = PHP_VERSION_ID >= 70400 ? '' : "\0";

        return \fgetcsv($handle, $length, $delimiter, $enclosure, $escapeCharacter);
    }

    /**
     * Wrapper around global function fputcsv()
     * @param resource $handle
     * @param array $fields
     * @param string|null $delimiter
     * @param string|null $enclosure
     * @return int
     * @see fputcsv()
     *
     */
    public function fputcsv($handle, array $fields, $delimiter = null, $enclosure = null)
    {
        // PHP uses '\' as the default escape character. This is not RFC-4180 compliant...
        // To fix that, simply disable the escape character.
        // @see https://bugs.php.net/bug.php?id=43225
        // @see http://tools.ietf.org/html/rfc4180
        $escapeCharacter = PHP_VERSION_ID >= 70400 ? '' : "\0";

        return \fputcsv($handle, $fields, $delimiter, $enclosure, $escapeCharacter);
    }

    /**
     * Wrapper around global function fwrite()
     * @param resource $handle
     * @param string $string
     * @return int
     * @see fwrite()
     *
     */
    public function fwrite($handle, $string)
    {
        return \fwrite($handle, $string);
    }

    /**
     * Wrapper around global function fclose()
     * @param resource $handle
     * @return bool
     * @see fclose()
     *
     */
    public function fclose($handle)
    {
        return \fclose($handle);
    }

    /**
     * Wrapper around global function rewind()
     * @param resource $handle
     * @return bool
     * @see rewind()
     *
     */
    public function rewind($handle)
    {
        return \rewind($handle);
    }

    /**
     * Wrapper around global function file_exists()
     * @param string $fileName
     * @return bool
     * @see file_exists()
     *
     */
    public function file_exists($fileName)
    {
        return \file_exists($fileName);
    }

    /**
     * Wrapper around global function file_get_contents()
     * @param string $filePath
     * @return string
     * @see file_get_contents()
     *
     */
    public function file_get_contents($filePath)
    {
        $realFilePath = $this->convertToUseRealPath($filePath);

        return \file_get_contents($realFilePath);
    }

    /**
     * Updates the given file path to use a real path.
     * This is to avoid issues on some Windows setup.
     *
     * @param string $filePath File path
     * @return string The file path using a real path
     */
    protected function convertToUseRealPath($filePath)
    {
        $realFilePath = $filePath;

        if ($this->isZipStream($filePath)) {
            if (\preg_match('/zip:\/\/(.*)#(.*)/', $filePath, $matches)) {
                $documentPath = $matches[1];
                $documentInsideZipPath = $matches[2];
                $realFilePath = 'zip://' . \realpath($documentPath) . '#' . $documentInsideZipPath;
            }
        } else {
            $realFilePath = \realpath($filePath);
        }

        return $realFilePath;
    }

    /**
     * Returns whether the given path is a zip stream.
     *
     * @param string $path Path pointing to a document
     * @return bool TRUE if path is a zip stream, FALSE otherwise
     */
    protected function isZipStream($path)
    {
        return (\strpos($path, 'zip://') === 0);
    }

    /**
     * Wrapper around global function feof()
     * @param resource $handle
     * @return bool
     * @see feof()
     *
     */
    public function feof($handle)
    {
        return \feof($handle);
    }

    /**
     * Wrapper around global function is_readable()
     * @param string $fileName
     * @return bool
     * @see is_readable()
     *
     */
    public function is_readable($fileName)
    {
        return \is_readable($fileName);
    }

    /**
     * Wrapper around global function basename()
     * @param string $path
     * @param string|null $suffix
     * @return string
     * @see basename()
     *
     */
    public function basename($path, $suffix = null)
    {
        return \basename($path, $suffix);
    }

    /**
     * Wrapper around global function header()
     * @param string $string
     * @return void
     * @see header()
     *
     */
    public function header($string)
    {
        \header($string);
    }

    /**
     * Wrapper around global function ob_end_clean()
     * @return void
     * @see ob_end_clean()
     *
     */
    public function ob_end_clean()
    {
        if (\ob_get_length() > 0) {
            \ob_end_clean();
        }
    }

    /**
     * Wrapper around global function iconv()
     * @param string $string The string to be converted
     * @param string $sourceEncoding The encoding of the source string
     * @param string $targetEncoding The encoding the source string should be converted to
     * @return string|bool the converted string or FALSE on failure.
     * @see iconv()
     *
     */
    public function iconv($string, $sourceEncoding, $targetEncoding)
    {
        return \iconv($sourceEncoding, $targetEncoding, $string);
    }

    /**
     * Wrapper around global function mb_convert_encoding()
     * @param string $string The string to be converted
     * @param string $sourceEncoding The encoding of the source string
     * @param string $targetEncoding The encoding the source string should be converted to
     * @return string|bool the converted string or FALSE on failure.
     * @see mb_convert_encoding()
     *
     */
    public function mb_convert_encoding($string, $sourceEncoding, $targetEncoding)
    {
        return \mb_convert_encoding($string, $targetEncoding, $sourceEncoding);
    }

    /**
     * Wrapper around global function stream_get_wrappers()
     * @return array
     * @see stream_get_wrappers()
     *
     */
    public function stream_get_wrappers()
    {
        return \stream_get_wrappers();
    }

    /**
     * Wrapper around global function function_exists()
     * @param string $functionName
     * @return bool
     * @see function_exists()
     *
     */
    public function function_exists($functionName)
    {
        return \function_exists($functionName);
    }
}
