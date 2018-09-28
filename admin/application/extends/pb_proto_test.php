<?php
/**
 * Auto generated from test.proto at 2016-04-19 16:28:14
 */

/**
 * PhoneNumber enum
 */
final class PhoneNumber
{
    const MOBILE = 0;
    const HOME = 1;
    const WORK = 2;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'MOBILE' => self::MOBILE,
            'HOME' => self::HOME,
            'WORK' => self::WORK,
        );
    }
}

/**
 * phonemsg message
 */
class Phonemsg extends \ProtobufMessage
{
    /* Field index constants */
    const NAME = 1;
    const EMAIL = 2;
    const PHONEDA = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::NAME => array(
            'name' => 'name',
            'required' => true,
            'type' => 7,
        ),
        self::EMAIL => array(
            'name' => 'email',
            'required' => false,
            'type' => 7,
        ),
        self::PHONEDA => array(
            'name' => 'phoneda',
            'repeated' => true,
            'type' => 5,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::NAME] = null;
        $this->values[self::EMAIL] = null;
        $this->values[self::PHONEDA] = array();
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::NAME, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        return $this->get(self::NAME);
    }

    /**
     * Sets value of 'email' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEmail($value)
    {
        return $this->set(self::EMAIL, $value);
    }

    /**
     * Returns value of 'email' property
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->get(self::EMAIL);
    }

    /**
     * Appends value to 'phoneda' list
     *
     * @param int $value Value to append
     *
     * @return null
     */
    public function appendPhoneda($value)
    {
        return $this->append(self::PHONEDA, $value);
    }

    /**
     * Clears 'phoneda' list
     *
     * @return null
     */
    public function clearPhoneda()
    {
        return $this->clear(self::PHONEDA);
    }

    /**
     * Returns 'phoneda' list
     *
     * @return int[]
     */
    public function getPhoneda()
    {
        return $this->get(self::PHONEDA);
    }

    /**
     * Returns 'phoneda' iterator
     *
     * @return ArrayIterator
     */
    public function getPhonedaIterator()
    {
        return new \ArrayIterator($this->get(self::PHONEDA));
    }

    /**
     * Returns element from 'phoneda' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return int
     */
    public function getPhonedaAt($offset)
    {
        return $this->get(self::PHONEDA, $offset);
    }

    /**
     * Returns count of 'phoneda' list
     *
     * @return int
     */
    public function getPhonedaCount()
    {
        return $this->count(self::PHONEDA);
    }
}
