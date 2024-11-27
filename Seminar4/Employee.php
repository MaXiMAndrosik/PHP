<?php

class Employee
{
    private string $name;
    private int $age;
    private int $salary;

    public function __construct($name, $age, $salary)
    {
        $this->name = $name;
        $this->age = $age;
        $this->salary = $salary;
    }

    public function setName(string $name):void {
        $this->name = $name;
    }

    public function getName():string {
        return $this->name;
    }

    public function setAge(int $age):void {
        $this->age = $age;
    }

    public function getAge():int {
        return $this->age;
    }

    public function setSalary(int $salary):void {
        $this->salary = $salary;
    }

    public function getSalary():int {
        return $this->salary;
    }

}

$person1 = new Employee('Денис', 25, 2000);
print($person1->getName().PHP_EOL);
print($person1->getAge().PHP_EOL);
print($person1->getSalary().PHP_EOL);

$person2 = new Employee('Вадим', 26, 2500);
$person2->setName('Олег');
$person2->setAge(30);
$person2->setSalary(1000);

print($person2->getName().PHP_EOL);
print($person2->getAge().PHP_EOL);
print($person2->getSalary().PHP_EOL);

$person3 = new Employee('Петр', 27, 2300);

print($person2->getSalary() + $person1->getSalary());
// var_dump($person1);
// var_dump($person2);
// var_dump($person3);