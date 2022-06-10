<?php

namespace App\Services;

class HelperService
{

    /**
     * Function for return response.
     *
     * @param [type] $result
     * @return void
     */
    public static function returnTrueResponse($result = null)
    {
        if (null == $result) {
            return array('success' => true);
        } else {
            return array('success' => true, 'result' => $result);
        }
    }

    /**
     * Function for return faild response.
     *
     * @return array
     */
    public static function returnFalseResponse($e = null)
    {
        if ($e == null) {
            return array('success' => false);
        } elseif (is_string($e)) {
            return array('success' => false, 'error' => $e);
        } else {
            return array('success' => false, 'error' => (config('app.debug')) ? ($e->getMessage() . " at " . $e->getLine() . " in " . $e->getFile()) : ("Something Went Wrong"));
        }
    }

}
