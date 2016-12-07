<?php
function getXmpData($filename, $chunk_size = 1024)
{
	if (!is_int($chunkSize)) {
		throw new RuntimeException('Expected integer value for argument #2 (chunkSize)');
	}

	if ($chunkSize < 12) {
		throw new RuntimeException('Chunk size cannot be less than 12 argument #2 (chunkSize)');
	}

	if (($file_pointer = fopen($filename, 'rb')) === FALSE) {
		throw new RuntimeException('Could not open file for reading');
	}

	$tag = '<x:xmpmeta';
	$buffer = false;

	// find open tag
	while ($buffer === false && ($chunk = fread($file_pointer, $chunk_size)) !== false) {
		if(strlen($chunk) <= 10) {
			break;
		}
		if(($position = strpos($chunk, $tag)) === false) {
			// if open tag not found, back up just in case the open tag is on the split.
			fseek($file_pointer, -10, SEEK_CUR);
		} else {
			$buffer = substr($chunk, $position);
		}
	}

	if($buffer === false) {
		fclose($file_pointer);
		return false;
	}

	$tag = '</x:xmpmeta>';
	$offset = 0;
	while (($position = strpos($buffer, $tag, $offset)) === false && ($chunk = fread($file_pointer, $chunk_size)) !== FALSE && !empty($chunk)) {
		$offset = strlen($buffer) - 12; // subtract the tag size just in case it's split between chunks.
		$buffer .= $chunk;
	}

	fclose($file_pointer);

	if($position === false) {
		// this would mean the open tag was found, but the close tag was not.  Maybe file corruption?
		throw new RuntimeException('No close tag found.  Possibly corrupted file.');
	} else {
		$buffer = substr($buffer, 0, $position + 12);
	}

	return $buffer;
}


/*

http://stackoverflow.com/questions/1578169/how-can-i-read-xmp-data-from-a-jpg-with-php

fonction à tester pour récupérer les xmp d' une photo

non intégrée dans les scripts

$filename doit-être une url
*/





?>