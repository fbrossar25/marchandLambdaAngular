<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", uniqueConstraints={@ORM\UniqueConstraint(name="NOM", columns={"NOM"})})
 * @ORM\Entity
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ARTICLE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="PRIX", type="decimal", precision=17, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=1024, nullable=false, options={"default"="Pas de description définie"})
     */
    private $description = 'Pas de description définie';


    /**
     * Get idArticle.
     *
     * @return int
     */
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Article
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prix.
     *
     * @param string $prix
     *
     * @return Article
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var string|null
     *
     * @ORM\Column(name="URL_IMAGE", type="string", length=1024, nullable=true)
     */
    private $urlImage;


    /**
     * Set urlImage.
     *
     * @param string|null $urlImage
     *
     * @return Article
     */
    public function setUrlImage($urlImage = null)
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    /**
     * Get urlImage.
     *
     * @return string|null
     */
    public function getUrlImage()
    {
        return $this->urlImage;
    }
}
