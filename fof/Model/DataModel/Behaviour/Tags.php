<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Model\DataModel\Behaviour;

use FOF30\Event\Observer;
use FOF30\Model\DataModel;
use JDatabaseQuery;

defined('_JEXEC') or die;

/**
 * FOF model behavior class to add Joomla! Tags support
 *
 * @since    2.1
 */
class Tags extends Observer
{
	/**
	 * The event which runs after binding data to the table
	 *
	 * @param   DataModel    &$model  The model which calls this event
	 * @param   object|array &$src    The data to bind
	 *
	 * @return  boolean  True on success
	 */
	public function onAfterBind(&$model, &$src)
	{
		// Bind tags
		if ($model->hasTags())
		{
			if ((!empty($src['tags']) && $src['tags'][0] != ''))
			{
				$model->newTags = $src['tags'];
			}

			// Check if the content type exists, and create it if it does not
			$model->checkContentType();

			$tagsTable = clone($model);

			$tagsHelper            = new \JHelperTags();
			$tagsHelper->typeAlias = $model->getContentType();

			if (!$tagsHelper->postStoreProcess($tagsTable))
			{
				throw new \Exception('Error storing tags');
			}
		}

		return true;
	}

	/**
	 * The event which runs before storing (saving) data to the database
	 *
	 * @param   DataModel  &$model  The model which calls this event
	 * @param   null|array $data    [Optional] Data to bind
	 *
	 * @return  boolean  True to allow saving
	 */
	public function onBeforeSave(&$model, &$data)
	{
		if ($model->hasTags())
		{
			$tagsHelper            = new \JHelperTags();
			$tagsHelper->typeAlias = $model->getContentType();

			// JHelperTags in Joomla! 3.1, it required tags in the metadata property.
			// TODO If this issue is fixed in Joomla! we need to remove this code
			$tagsTable = clone($model);
			$tagsHelper->preStoreProcess($tagsTable);
		}
	}

	/**
	 * The event which runs after deleting a record
	 *
	 * @param   DataModel &$model The model which calls this event
	 * @param   integer   $oid    The PK value of the record which was deleted
	 *
	 * @return  boolean  True to allow the deletion without errors
	 */
	public function onAfterDelete(&$model, $oid)
	{
		// If this resource has tags, delete the tags first
		if ($model->hasTags())
		{
			$tagsHelper            = new \JHelperTags();
			$tagsHelper->typeAlias = $model->getContentType();

			if (!$tagsHelper->deleteTagData($model, $oid))
			{
				throw new \Exception('Error deleting Tags');
			}
		}
	}

	/**
	 * This event runs after publishing a record in a model
	 *
	 * @param   DataModel  &$model  The model which calls this event
	 *
	 * @return  void
	 */
	public function onAfterPublish(&$model)
	{
		$model->updateUcmContent();
	}

	/**
	 * This event runs after unpublishing a record in a model
	 *
	 * @param   DataModel  &$model  The model which calls this event
	 *
	 * @return  void
	 */
	public function onAfterUnpublish(&$model)
	{
		$model->updateUcmContent();
	}
}