DKCalculatorBundle
==================
This is a simple bundle that allows you to use dynamically calculated properties in Doctrine entities.

Installation
============

TODO

Usage
=====
Suppose you have a `User` entity, and a `Transaction` entity with a `@ManyToOne` association to `User`.  Further
suppose that you want to have a `balance` property on `User` which adds up all the user's transactions.  It would
be possible to do this using a bi-directional association and adding up the values in PHP, but this would be very
inefficient compared to using DQL.  It would also be possible to make a custom repository (or a custom service)
that hydrates the entity by hand, but this means you need to access the entity in a special way and if you are
using serialization (e.g. with https://github.com/schmittjoh/JMSSerializerBundle) this can get quite complicated.

This bundle offers another solution:

```php
use DK\CalculatorBundle\Annotation\Calculator;

/**
 * @ORM\Entity
 */
class User {

    /**
     * @Calculator(class="UserCalculator")
     */
    protected $balance;
    public function setBalance($value) { $this->balance = $value; return $this; }
    public function getBalance() { return $this->balance; }

}

class UserCalculator {

    public function getBalance(User $user, EntityManager $em) {
        $query = $em->createQuery("SELECT SUM(t.value) FROM Transaction t JOIN t.user u WHERE u=:user");
        $query->setParameter('user', $user);
        return (float)$query->getSingleScalarResult();
    }

}
```

```php
/**
 * @ORM\Entity
 */
class Transaction {

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @JMS\Expose
     */
    protected $value;
    public function setValue($value) { $this->value = $value; return $this; }
    public function getValue() { return $this->value; }

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;
    public function setUser($value) { $this->user = $value; return $this; }
    public function getUser() { return $this->user; }
    
}
```