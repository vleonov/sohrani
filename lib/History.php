<?php

class History
{
    const MODEL_PHOTO = 1;
    const MODEL_ALBUM = 2;

    public static function add($model, $ids, array $values)
    {
        $mHistory = new M_History();

        $mHistory->model = $model;
        $mHistory->ids = (array) $ids;
        $mHistory->values = $values;

        return $mHistory->save();
    }

    public static function undo($id)
    {
        $mHistory = new M_History($id);
        if (!$mHistory->id) {
            return false;
        }

        switch ($mHistory->model) {
            case self::MODEL_PHOTO:
                $lModels = new L_Photos(array('id' => $mHistory->ids));
                break;
            case self::MODEL_ALBUM:
                $lModels = new L_Albums(array('id' => $mHistory->ids));
                break;
            default:
                return false;
        }

        $db = Database::get();
        $db->begin();

        try {

            foreach ($lModels as $mModel) {
                /** @var $mModel Model */
                $mModel->fromArray(
                    $mHistory->values + $mModel->toArray()
                )->save();
            }

            $db->commit();
            $result = true;
        } catch (PDOException $e) {
            $db->rollback();
            $result = false;
        }

        return $result;
    }
}