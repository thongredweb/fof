<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Form\Field;

use FOF30\Form\FieldInterface;
use FOF30\Form\Form;
use FOF30\Model\DataModel;
use \JHtml;
use \JText;

defined('_JEXEC') or die;

\JFormHelper::loadFieldClass('imagelist');

/**
 * Form Field class for the FOF framework
 * Images field.
 */
class Images extends ImageList
{
	/**
	 * Method to get the field input markup.
	 *
	 * @param   array   $fieldOptions  Options to be passed into the field
	 *
	 * @return  string  The field HTML
	 */
	public function getFieldContents(array $fieldOptions = array())
	{
		$id    = isset($fieldOptions['id']) ? 'id="' . $fieldOptions['id'] . '" ' : '';
		$class = $this->class . (isset($fieldOptions['class']) ? ' ' . $fieldOptions['class'] : '');

		if (!is_array($this->value))
		{
			$this->value = (array) $this->value;
		}

		$html = '<span ' . ($id ? $id : '') . 'class="'. $class . '">';

		foreach ($this->value as $image)
		{
			$imgattr = array();

			if ($class)
			{
				$imgattr['class'] = $class;
			}

			if ($this->element['style'])
			{
				$imgattr['style'] = (string) $this->element['style'];
			}

			if ($this->element['width'])
			{
				$imgattr['width'] = (string) $this->element['width'];
			}

			if ($this->element['height'])
			{
				$imgattr['height'] = (string) $this->element['height'];
			}

			if ($this->element['align'])
			{
				$imgattr['align'] = (string) $this->element['align'];
			}

			if ($this->element['rel'])
			{
				$imgattr['rel'] = (string) $this->element['rel'];
			}

			if ($this->element['alt'])
			{
				$alt = JText::_((string) $this->element['alt']);
			}
			else
			{
				$alt = null;
			}

			if ($this->element['title'])
			{
				$imgattr['title'] = JText::_((string) $this->element['title']);
			}

			$path = (string) $this->element['directory'];
			$path = trim($path, '/' . DIRECTORY_SEPARATOR);

			if ($image && file_exists(JPATH_ROOT . '/' . $path . '/' . $image))
			{
				$src = $this->form->getContainer()->platform->URIroot() . '/' . $path . '/' . $image;
			}
			else
			{
				$src = '';
			}

			$html .= JHtml::image($src, $alt, $imgattr);
		}

		$html = '</span>';

		return $html;
	}
}
