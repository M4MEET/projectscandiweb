<?php
class Db
{
    /**
     * @var PDO
     */
    private static $con;

    /**
     * Connect to DB
     *
     * @param $hostname
     * @param $user
     * @param $pass
     * @param $dbName
     */
    public static function connect($hostname, $user, $pass,$dbName)
    {
        self::$con = new PDO("mysql:host=$hostname;dbname=$dbName", $user, $pass);
    }

    /**
     * Insert into table
     *
     * @param $tableName
     * @param array $params
     */
    public static function insert($tableName ,array $params)
    {
        $num = count($params);

        $array = array_fill(0,$num,'?');

        $query = sprintf(/** @lang text */
                'INSERT INTO %s(%s) VALUES(%s)',
                $tableName,implode(', ', array_keys($params)),
                implode(',', $array));

        $sth = self::$con->prepare($query);
        $sth->execute(array_values($params));
    }
}

abstract class Product
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var float
     */
    protected $price;

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param $title string
     *
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get Price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set Price
     *
     * @param $price float
     *
     * @return float
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }
}

class Furniture extends Product
{
    const MATERIAL_WOOD = 'wood';
    const MATERIAL_PLASTIC = 'plastic';

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string
     */
    protected $material;

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param string $material
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    }

    /**
     * Get all attributes
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return array(
            'material' => $this->material,
            'size' => $this->size,
        );
    }

    /**
     * Insert data to DB
     */
    public function save()
    {
        Db::insert('catalog', array(
            'TITLE' => $this->title,
            'PRICE' => $this->price,
            'SIZE' => $this->size,
            'MATERIAL' => $this->material,
        ));
    }
}

class Disc extends Product
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $manufacturer;

    /**
     * @override getTitle
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title.' '.$this->getSize();
    }
    
    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size.' MB';
    }
    
    /**
     * Set size
     *
     * @param $size string
     *
     * @return int
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get manufacturer
     *
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set manufacturer
     *
     * @param $manufacturer string
     *
     * @return string
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }
    
    /**
     * Get all attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return array(
            'manufacturer' => $this->manufacturer,
            'size' => $this->getSize(),
        );
    }

    /**
     * Save data to DB
     */
    public function save()
    {
        Db::insert('catalog', array(
            'TITLE' => $this->title,
            'PRICE' => $this->price,
            'SIZEFORDISC' => $this->size,
            'MANUFACTURER' => $this->manufacturer,
        ));
    }
}

Db::connect('localhost', 'root', '', 'catalog');

$table = new Furniture();
$table->setTitle('Table');
$table->setPrice(499.99);
$table->setSize('0.5x2x1');
$table->setMaterial(Furniture::MATERIAL_WOOD);
$table->save();

$cd = new Disc();
$cd->setTitle('Cd disc');
$cd->setPrice(9.99);
$cd->setSize(720);
$cd->setManufacturer('acme');
$cd->save();
