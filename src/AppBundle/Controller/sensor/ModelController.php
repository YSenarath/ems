<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/17/2015
 * Time: 9:57 AM
 */

namespace AppBundle\Controller\sensor;


use Doctrine\DBAL\Connection;
use AppBundle\Entity\sensor\Model;
use Doctrine\ORM\ORMException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ModelController
{

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function searchModel($model_id)
    {
        $s =$this->connection->fetchAssoc('SELECT * FROM sensor_model WHERE model_id = ?', array($model_id));

        //print_r($result);
        $model = new Model();

        if ($s != null) {
            $model->setModelId($s["model_id"]);
            $model->setManufacture($s["manufacturer"]);
            $model->setDetRange($s["detection_range"]);
            $model->setUnit($s["unit"]);

        }else{
            return false;
        }

        return $model;
    }

    public function getAllModels()
    {

        $result =$this->connection->executeQuery('SELECT * FROM sensor_model ');
        $result = $result->fetchAll();

        //print_r($result);
        $models[] = new Model();

        foreach ($result as $s) {
            if ($s != null) {
                $model = new Model();
                $model->setModelId($s["model_id"]);
                $model->setManufacture($s["manufacturer"]);
                $model->setDetRange($s["detection_range"]);
                $model->setUnit($s["unit"]);
                $models[] = $model;
            }
        }

        return $models;
    }

    public function getAllModelNames()
    {

        $result =$this->connection->executeQuery('SELECT model_id , manufacturer  FROM sensor_model ORDER BY manufacturer ');
        $result = $result->fetchAll();

        //print_r($result);

        $tempManufacture = null;
        $tempModel = null;
        $models = null;
        foreach ($result as $s) {
            if ($s != null) {
                if ($tempManufacture != $s["manufacturer"] ) {

                    if ($tempModel != null) {
                        $models[$tempManufacture] = $tempModel;
                        $tempModel = null;
                    }
                    $tempManufacture = $s["manufacturer"];
                }
                $tempModel[$s["model_id"]] = $s["model_id"];


            }
        }

        return $models;
    }

    public function addModelAction(Model $model)
    {
        $this->connection->beginTransaction();

        try{
            $statement = $this->connection->prepare('INSERT INTO sensor_model (model_id ,manufacturer , unit , detection_range ) VALUES (?,?,?,?)');

            $statement->bindValue(1, $model->getModelId());
            $statement->bindValue(2, $model->getManufacture());
            $statement->bindValue(3, $model->getUnit());
            $statement->bindValue(4, $model->getDetRange());

            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    public function updateModelAction(Model $model)
    {
        $this->connection->beginTransaction();

        try{
            $statement = $this->connection->prepare('UPDATE sensor_model SET manufacturer = ?, unit = ? , detection_range= ? WHERE model_id =?');

            $statement->bindValue(4, $model->getModelId());
            $statement->bindValue(1, $model->getManufacture());
            $statement->bindValue(2, $model->getUnit());
            $statement->bindValue(3, $model->getDetRange());

            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }


    public function removeModel($modelId){

        $this->connection->beginTransaction();

        try{
            $statement = $this->connection->prepare('DELETE FROM sensor_model WHERE model_id = ?');
            $statement->bindValue(1, $modelId);
            $statement->execute();
            $this->connection->commit();

            return true;
        } catch(ORMException $e) {
            $this->connection->rollBack();
            // throw $e;
            return false;
        }catch (\PDOException $e){
            $this->connection->rollBack();
            // throw $e;
            return false;
        }catch (ForeignKeyConstraintViolationException $e){
            $this->connection->rollBack();
            // throw $e;
            return false;
        }


    }
}