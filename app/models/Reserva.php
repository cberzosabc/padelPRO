<?php

class Reserva{
    private $id;
    private $idUsuario;
    private $idTramo;
    

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of idTramo
     */
    public function getIdTramo()
    {
        return $this->idTramo;
    }

    /**
     * Set the value of idTramo
     */
    public function setIdTramo($idTramo): self
    {
        $this->idTramo = $idTramo;

        return $this;
    }
}