<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employee = new Employee();
        $employee->setAge(35.87);
        $employee->setBirthDate(\DateTime::createFromFormat('m/d/Y', '9/21/1982'));
        $employee->setBirthTime(\DateTime::createFromFormat('h:i:s A', '01:53:13 AM'));
        $employee->setCity('Clymer');
        $employee->setCounty('Chautauqua');
        $employee->setEmail('serafina.bumgarner@exxonmobil.com');
        $employee->setFirstname('Serafina');
        $employee->setGender('F');
        $employee->setJoinDate(\DateTime::createFromFormat('m/d/Y', '2/1/2008'));
        $employee->setLastname('Bumgarner');
        $employee->setMiddleInitial('I');
        $employee->setPrefix('Mrs.');
        $employee->setPhone('212-376-9125');
        $employee->setPlace('Clymer');
        $employee->setRegion('Northeast');
        $employee->setStatus(null);
        $employee->setTenure(9.49);
        $employee->setEid(198429);
        $employee->setUsername('sibumgarner');
        $employee->setZipcode(14724);
        $manager->persist($employee);

        $employee = new Employee();
        $employee->setAge(50.26);
        $employee->setBirthDate(\DateTime::createFromFormat('m/d/Y', '5/8/1976'));
        $employee->setBirthTime(\DateTime::createFromFormat('h:i:s A', '06:03:23 AM'));
        $employee->setCity('Glenside');
        $employee->setCounty('Montgomery');
        $employee->setEmail('juliette.rojo@yahoo.co.uk');
        $employee->setFirstname('Juliette');
        $employee->setGender('M');
        $employee->setJoinDate(\DateTime::createFromFormat('m/d/Y', '6/4/2011'));
        $employee->setLastname('Rojo');
        $employee->setMiddleInitial('I');
        $employee->setPrefix('Mrs.');
        $employee->setPhone('212-376-9125');
        $employee->setPlace('Glenside');
        $employee->setRegion('Northeast');
        $employee->setStatus(null);
        $employee->setTenure(6.15);
        $employee->setEid(178566);
        $employee->setUsername('jmrojo');
        $employee->setZipcode(19038);
        $manager->persist($employee);

        $manager->flush();
    }
}
