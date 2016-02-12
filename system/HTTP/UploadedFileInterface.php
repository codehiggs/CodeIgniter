<?php namespace CodeIgniter\HTTP;

/**
 * Value object representing a single file uploaded through an
 * HTTP request. Used by the IncomingRequest class to
 * provide files.
 *
 * Typically, implementors will extend the SplFileInfo class.
 *
 * @package CodeIgniter\HTTP
 */
interface UploadedFileInterface
{
	/**
	 * Accepts the file information as would be filled in from the $_FILES array.
	 *
	 * @param string $path         The temporary location of the uploaded file.
	 * @param string $originalName The client-provided filename.
	 * @param string $mimeType     The type of file as provided by PHP
	 * @param int    $size         The size of the file, in bytes
	 * @param int    $error        The error constant of the upload (one of PHP's UPLOADERRXXX constants)
	 */
	public function __construct(string $path, string $originalName, string $mimeType = null, int $size = null, int $error = null);

	//--------------------------------------------------------------------

	/**
	 * Move the uploaded file to a new location.
	 *
	 * $targetPath may be an absolute path, or a relative path. If it is a
	 * relative path, resolution should be the same as used by PHP's rename()
	 * function.
	 *
	 * The original file MUST be removed on completion.
	 *
	 * If this method is called more than once, any subsequent calls MUST raise
	 * an exception.
	 *
	 * When used in an SAPI environment where $_FILES is populated, when writing
	 * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
	 * used to ensure permissions and upload status are verified correctly.
	 *
	 * If you wish to move to a stream, use getStream(), as SAPI operations
	 * cannot guarantee writing to stream destinations.
	 *
	 * @see http://php.net/is_uploaded_file
	 * @see http://php.net/move_uploaded_file
	 *
	 * @param string $targetPath Path to which to move the uploaded file.
	 * @param string $name       the name to rename the file to.
	 * @param int    $perm       The file permissions to set the file to once moved.
	 *                           Used to make the file un-executable for security.
	 *
	 * @throws \InvalidArgumentException if the $path specified is invalid.
	 * @throws \RuntimeException on any error during the move operation.
	 * @throws \RuntimeException on the second or subsequent call to the method.
	 */
	public function move(string $targetPath, string $name = null, int $perm = 0644): bool;

	//--------------------------------------------------------------------

	/**
	 * Retrieve the file size.
	 *
	 * Implementations SHOULD return the value stored in the "size" key of
	 * the file in the $_FILES array if available, as PHP calculates this based
	 * on the actual size transmitted.
	 *
	 * @return int|null The file size in bytes or null if unknown.
	 */
	public function getSize(): int;

	//--------------------------------------------------------------------

	/**
	 * Retrieve the error associated with the uploaded file.
	 *
	 * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
	 *
	 * If the file was uploaded successfully, this method MUST return
	 * UPLOAD_ERR_OK.
	 *
	 * Implementations SHOULD return the value stored in the "error" key of
	 * the file in the $_FILES array.
	 *
	 * @see http://php.net/manual/en/features.file-upload.errors.php
	 * @return int One of PHP's UPLOAD_ERR_XXX constants.
	 */
	public function getError();

	//--------------------------------------------------------------------

	/**
	 * Retrieve the filename sent by the client.
	 *
	 * Do not trust the value returned by this method. A client could send
	 * a malicious filename with the intention to corrupt or hack your
	 * application.
	 *
	 * Implementations SHOULD return the value stored in the "name" key of
	 * the file in the $_FILES array.
	 *
	 * @return string|null The filename sent by the client or null if none
	 *     was provided.
	 */
	public function getName(): string;

	//--------------------------------------------------------------------

	/**
	 * Gets the temporary filename where the file was uploaded to.
	 *
	 * @return string
	 */
	public function getTempName(): string;

	//--------------------------------------------------------------------

	/**
	 * Generates a random names based on a simple hash and the time, with
	 * the correct file extension attached.
	 *
	 * @return string
	 */
	public function getRandomName(): string;

	//--------------------------------------------------------------------

	/**
	 * Attempts to determine the file extension based on the trusted
	 * getMimeType() method. If the mime type is unknown, will return null.
	 *
	 * @return string
	 */
	public function getExtension(): string;

	//--------------------------------------------------------------------

	/**
	 * Returns the original file extension, based on the file name that
	 * was uploaded. This is NOT a trusted source.
	 * For a trusted version, use guessExtension() instead.
	 *
	 * @return string|null
	 */
	public function getClientExtension(): string;

	//--------------------------------------------------------------------

	/**
	 * Retrieve the media type of the file. SHOULD not use information from
	 * the $_FILES array, but should use other methods to more accurately
	 * determine the type of file, like finfo, or mime_content_type().
	 *
	 * @return string|null The media type sent by the client or null if none
	 *     was provided.
	 */
	public function getType(): string;

	//--------------------------------------------------------------------

	/**
	 * Returns the mime type as provided by the client.
	 * This is NOT a trusted value.
	 * For a trusted version, use getMimeType() instead.
	 *
	 * @return string|null
	 */
	public function getClientType(): string;

	//--------------------------------------------------------------------

	/**
	 * Attempts to determine whether this file is an image or not. Does
	 * not trust the file extension, but uses other tools to better
	 * verify if it is an image or not.
	 *
	 * @return bool
	 */
	public function isImage(): bool;

	//--------------------------------------------------------------------

	/**
	 * Returns whether the file was uploaded successfully, based on whether
	 * it was uploaded via HTTP and has no errors.
	 *
	 * @return bool
	 */
	public function isValid(): bool;

	//--------------------------------------------------------------------

}