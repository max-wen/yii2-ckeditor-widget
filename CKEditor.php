<?php
/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace maxwen\ckeditor;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * CKEditor renders a CKEditor js plugin for classic editing.
 * @see http://docs.ckeditor.com/
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package maxwen\ckeditor
 */
class CKEditor extends InputWidget
{
    use CKEditorTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }

        var_dump($this->clientOptions);
		exit;

        $this->registerPlugin();
    }

    /**
     * Registers CKEditor plugin
     * @codeCoverageIgnore
     */
    protected function registerPlugin()
    {
        $js = [];

        $view = $this->getView();

        CKEditorWidgetAsset::register($view);

        $id = $this->options['id'];

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';

        $js[] = "CKEDITOR.replace('$id', $options);";
        $js[] = "maxwen.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($this->clientOptions['filebrowserUploadUrl'])) {
            $js[] = "maxwen.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
    }
}