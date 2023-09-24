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
        $employee->setUid(198429);
        $employee->setUsername('sibumgarner');
        $employee->setZipcode(14724);

        $this->addReference('employee-1', $employee);
        $manager->persist($employee);

        $employee2 = new Employee();

        $employee2->setAge(50.26);
        $employee2->setBirthDate(\DateTime::createFromFormat('m/d/Y', '5/8/1976'));
        $employee2->setBirthTime(\DateTime::createFromFormat('h:i:s A', '06:03:23 AM'));
        $employee2->setCity('Glenside');
        $employee2->setCounty('Montgomery');
        $employee2->setEmail('juliette.rojo@yahoo.co.uk');
        $employee2->setFirstname('Juliette');
        $employee2->setGender('M');
        $employee2->setJoinDate(\DateTime::createFromFormat('m/d/Y', '6/4/2011'));
        $employee2->setLastname('Rojo');
        $employee2->setMiddleInitial('I');
        $employee2->setPrefix('Mrs.');
        $employee2->setPhone('212-376-9125');
        $employee2->setPlace('Glenside');
        $employee2->setRegion('Northeast');
        $employee2->setStatus(null);
        $employee2->setTenure(6.15);
        $employee2->setUid(178566);
        $employee2->setUsername('jmrojo');
        $employee2->setZipcode(19038);

        $this->addReference('employee-2', $employee2);
        $manager->persist($employee2);

        $manager->flush();
    }
}
