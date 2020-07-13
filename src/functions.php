<?php

/**
 * @param array $options
 * @return string
 */
function multi_menu(array $options)
{
    $widgetConfig = !empty($options['config']) ? $options['config'] : config('multimenu');
    $widgetData = !empty($options['data']) ? $options['data'] : [];
    $widgetAdditionData = !empty($options['additionData']) ? $options['additionData'] : [];

    return (new Itstructure\MultiMenu\MultiMenuWidget($widgetConfig))->run($widgetData, $widgetAdditionData);
}
