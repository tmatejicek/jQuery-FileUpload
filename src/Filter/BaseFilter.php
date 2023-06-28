<?php declare(strict_types=1);

namespace Zet\FileUpload\Filter;

use Nette\Http\FileUpload;
use Nette\SmartObject;
use Nette\Utils\Arrays;

/**
 * Class BaseFilter
 *
 * @author  Zechy <email@zechy.cz>
 */
abstract class BaseFilter implements IMimeTypeFilter
{

	use SmartObject;

	/**
	 * Ověří mimetype předaného souboru.
	 *
	 * @param FileUpload $file Nahraný soubor k ověření.
	 * @return bool Má soubor správný mimetype?
	 */
	public function checkType(FileUpload $file): bool
	{
		if (in_array((string)$file->getContentType(), array_keys($this->getMimeTypes()), true)) {
			return true;
		} else {
			// Pokud se nepodaří ověřit mimetype, ověříme alespoň koncovku.
			return in_array($this->getExtension($file->getUntrustedName()), array_unique($this->getMimeTypes()), true);
		}
	}

	/**
	 * Vrátí seznam povolených typů souborů s jejich typickou koncovkou.
	 *
	 * @return string[]
	 * @example array("text/plain" => "txt")
	 */
	abstract protected function getMimeTypes(): array;

	/**
	 * Vrátí koncovku souboru.
	 */
	private function getExtension(string $filename): string
	{
		$exploded = explode('.', $filename);

		return $exploded[count($exploded) - 1];
	}

	/**
	 * Vrátí seznam povolených typů souborů.
	 */
	public function getAllowedTypes(): string
	{
		return implode(', ', array_unique($this->getMimeTypes()));
	}

}
