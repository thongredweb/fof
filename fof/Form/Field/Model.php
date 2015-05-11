<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Form\Field;

use FOF30\Container\Container;
use FOF30\Form\FieldInterface;
use FOF30\Form\Form;
use FOF30\Model\DataModel;
use \JHtml;
use \JText;

defined('_JEXEC') or die;

\JFormHelper::loadFieldClass('list');

/**
 * Form Field class for FOF
 * Generic list from a model's results
 */
class Model extends GenericList implements FieldInterface
{
	/**
	 * @var  string  Static field output
	 */
	protected $static;

	/**
	 * @var  string  Repeatable field output
	 */
	protected $repeatable;

	/**
	 * The Form object of the form attached to the form field.
	 *
	 * @var    Form
	 */
	protected $form;

	/**
	 * A monotonically increasing number, denoting the row number in a repeatable view
	 *
	 * @var  int
	 */
	public $rowid;

	/**
	 * The item being rendered in a repeatable form field
	 *
	 * @var  DataModel
	 */
	public $item;

	/**
	 * Options loaded from the model, cached for efficiency
	 *
	 * @var null|array
	 */
	protected $loadedOptions = null;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   2.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'static':
				if (empty($this->static))
				{
					$this->static = $this->getStatic();
				}

				return $this->static;
				break;

			case 'repeatable':
				if (empty($this->repeatable))
				{
					$this->repeatable = $this->getRepeatable();
				}

				return $this->repeatable;
				break;

			default:
				return parent::__get($name);
		}
	}

	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getStatic()
	{
		$class = $this->class ? ' class="' . $this->class . '"' : '';

		return '<span id="' . $this->id . '" ' . $class . '>' .
			htmlspecialchars(GenericList::getOptionName($this->getOptions(), $this->value), ENT_COMPAT, 'UTF-8') .
			'</span>';
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getRepeatable()
	{
		// Get field parameters
		$class					= $this->class ? $this->class : $this->id;
		$format_string			= $this->element['format'] ? (string) $this->element['format'] : '';
		$link_url				= $this->element['url'] ? (string) $this->element['url'] : '';
		$empty_replacement		= $this->element['empty_replacement'] ? (string) $this->element['empty_replacement'] : '';

		if ($link_url && ($this->item instanceof DataModel))
		{
			$link_url = $this->parseFieldTags($link_url);
		}
		else
		{
			$link_url = false;
		}

		if ($this->element['empty_replacement'])
		{
			$empty_replacement = (string) $this->element['empty_replacement'];
		}

		$value = GenericList::getOptionName($this->getOptions(), $this->value);

		// Get the (optionally formatted) value
		if (!empty($empty_replacement) && empty($value))
		{
			$value = JText::_($empty_replacement);
		}

		if (empty($format_string))
		{
			$value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
		}
		else
		{
			$value = sprintf($format_string, $value);
		}

		// Create the HTML
		$html = '<span class="' . $class . '">';

		if ($link_url)
		{
			$html .= '<a href="' . $link_url . '">';
		}

		$html .= $value;

		if ($link_url)
		{
			$html .= '</a>';
		}

		$html .= '</span>';

		return $html;
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions($forceReset = false)
	{
		static $loadedOptions = array();

		$myFormKey = $this->form->getName();

		if ($forceReset && isset($loadedOptions[$myFormKey]))
		{
			unset($loadedOptions[$myFormKey]);
		}

		if (!isset($loadedOptions[$myFormKey]))
		{
			$options = array();

			// Initialize some field attributes.
			$key = $this->element['key_field'] ? (string) $this->element['key_field'] : 'value';
			$value = $this->element['value_field'] ? (string) $this->element['value_field'] : (string) $this->element['name'];
			$translate = $this->element['translate'] ? (string) $this->element['translate'] : false;
			$applyAccess = $this->element['apply_access'] ? (string) $this->element['apply_access'] : 'false';
			$modelName = (string) $this->element['model'];
			$nonePlaceholder = (string) $this->element['none'];

			if (!empty($nonePlaceholder))
			{
				$options[] = JHtml::_('select.option', null, JText::_($nonePlaceholder));
			}

			// Process field atrtibutes
			$applyAccess = strtolower($applyAccess);
			$applyAccess = in_array($applyAccess, array('yes', 'on', 'true', '1'));

			// Explode model name into component name and prefix
			$componentName = $this->form->getContainer()->componentName;
			$mName = $modelName;

			if (strpos($modelName, '.') !== false)
			{
				list ($componentName, $mName) = explode('.', $mName, 2);
			}

			// Get the applicable container
			$container = $this->form->getContainer();

			if ($componentName != $container->componentName)
			{
				$container = Container::getInstance($componentName);
			}

			/** @var DataModel $model */
			$model = $container->factory->model($mName)->setIgnoreRequest(true)->savestate(false);

			// Get the model object
			if ($applyAccess)
			{
				$model->applyAccessFiltering();
			}

			// Process state variables
			/** @var \SimpleXMLElement $stateoption */
			foreach ($this->element->children() as $stateoption)
			{
				// Only add <option /> elements.
				if ($stateoption->getName() != 'state')
				{
					continue;
				}

				$stateKey = (string) $stateoption['key'];
				$stateValue = (string) $stateoption;

				$model->setState($stateKey, $stateValue);
			}

			// Set the query and get the result list.
			$items = $model->get(true);

			// Build the field options.
			if (!empty($items))
			{
				foreach ($items as $item)
				{
					if ($translate == true)
					{
						$options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
					}
					else
					{
						$options[] = JHtml::_('select.option', $item->$key, $item->$value);
					}
				}
			}

			// Merge any additional options in the XML definition.
			$options = array_merge(parent::getOptions(), $options);

			$loadedOptions[$myFormKey] = $options;
		}

		return $loadedOptions[$myFormKey];
	}

	/**
	 * Replace string with tags that reference fields
	 *
	 * @param   string  $text  Text to process
	 *
	 * @return  string         Text with tags replace
	 */
	protected function parseFieldTags($text)
	{
		$ret = $text;

		// Replace [ITEM:ID] in the URL with the item's key value (usually:
		// the auto-incrementing numeric ID)
		$keyfield = $this->item->getKeyName();
		$replace  = $this->item->$keyfield;
		$ret = str_replace('[ITEM:ID]', $replace, $ret);

		// Replace the [ITEMID] in the URL with the current Itemid parameter
		$ret = str_replace('[ITEMID]', $this->form->getContainer()->input->getInt('Itemid', 0), $ret);

		// Replace other field variables in the URL
		$fields = $this->item->getTableFields();

		foreach ($fields as $fielddata)
		{
			$fieldname = $fielddata->Field;

			if (empty($fieldname))
			{
				$fieldname = $fielddata->column_name;
			}

			$search    = '[ITEM:' . strtoupper($fieldname) . ']';
			$replace   = $this->item->$fieldname;

			if (!is_string($replace))
			{
				continue;
			}

			$ret  = str_replace($search, $replace, $ret);
		}

		return $ret;
	}
}
