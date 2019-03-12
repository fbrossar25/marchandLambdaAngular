<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="CLIENT", uniqueConstraints={@ORM\UniqueConstraint(name="EMAIL", columns={"EMAIL"})})
 * @ORM\Entity
 */
class Client
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_CLIENT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idClient;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="PRENOM", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMUNE", type="string", length=255, nullable=false)
     */
    private $commune;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_POSTAL", type="string", length=255, nullable=false)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="VOIE", type="string", length=255, nullable=false)
     */
    private $voie;

    /**
     * @var string
     *
     * @ORM\Column(name="NUMERO_VOIE", type="string", length=255, nullable=false)
     */
    private $numeroVoie;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="PASS", type="string", length=255, nullable=false)
     */
    private $pass;

    /**
     * @var string
     *
     * @ORM\Column(name="TELEPHONE", type="string", length=255, nullable=true)
     */
    private $telephone;


    /**
     * Get idClient
     *
     * @return integer
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set commune
     *
     * @param string $commune
     *
     * @return Client
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return string
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set voie
     *
     * @param string $voie
     *
     * @return Client
     */
    public function setVoie($voie)
    {
        $this->voie = $voie;

        return $this;
    }

    /**
     * Get voie
     *
     * @return string
     */
    public function getVoie()
    {
        return $this->voie;
    }

    /**
     * Set numeroVoie
     *
     * @param string $numeroVoie
     *
     * @return Client
     */
    public function setNumeroVoie($numeroVoie)
    {
        $this->numeroVoie = $numeroVoie;

        return $this;
    }

    /**
     * Get numeroVoie
     *
     * @return string
     */
    public function getNumeroVoie()
    {
        return $this->numeroVoie;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pass
     *
     * @param string $pass
     *
     * @return Client
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Client
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
}
