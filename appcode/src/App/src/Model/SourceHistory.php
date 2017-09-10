<?php

namespace App\Model;

class SourceHistory extends ModelAbstract
{

    protected $id = null;

    protected $sourceId = null;

    protected $url = null;

    protected $message = null;

    protected $statusId = null;

    protected $date = null;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getStatusId()
    {
        return $this->statusId;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }


}

