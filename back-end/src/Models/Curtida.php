<?php
    namespace APBPDN\Models;

    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\Entity;
    use Doctrine\ORM\Mapping\GeneratedValue;
    use Doctrine\ORM\Mapping\Id;
    use Doctrine\ORM\Mapping\ManyToOne;
    use DomainException;

    #[Entity()]
    class Curtida
    {
        #[Id, GeneratedValue(), Column()]
        public int $id;

        #[ManyToOne(Publicacao::class, inversedBy:'curtidas')]
        private Publicacao $publicacao;

        #[ManyToOne(targetEntity: Usuario::class, inversedBy:'curtidas')]
        private Usuario $usuario;

        /** @throws DomainException */
        public function __construct(Publicacao $publicacao, Usuario $usuario)
        {
            $this->publicacao = $publicacao;
            $this->usuario = $usuario;

            $usuario->setCurtida($this);
        }

        public function getPublicacao(): Publicacao
        {
            return $this->publicacao;
        }

        public function getUsuario(): Usuario
        {
            return $this->usuario;
        }
    }
?>