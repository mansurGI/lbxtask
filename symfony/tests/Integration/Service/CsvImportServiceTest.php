<?php

namespace App\Tests\Integration\Service;

use App\Entity\Employee;
use App\Service\CsvImportService\CsvImportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvImportServiceTest extends KernelTestCase
{
    /**
     * @covers \App\Service\CsvImportService::process()
     * @dataProvider data()
     */
    public function testProcess(string $csv, $insertions):void
    {
        $csvImportService = self::getContainer()->get(CsvImportService::class);
        $csvImportService->process(fopen('data://text/plain;base64,' . base64_encode($csv), 'r'));

        /** @var EntityManagerInterface $doctrine */
        $doctrine = self::getContainer()->get(EntityManagerInterface::class);

        //fixtures from test.php will add 2 employees TODO: database purge, fixtures before each test
        $this->assertCount($insertions + 2, $doctrine->getRepository(Employee::class)->findAll());
    }

    private function data(): array
    {
        return [
            [
                'csv' => 'csv-with-no-data-will-cause-validation-error',
                'insertions' => 0,
            ],
            [
                'csv' => implode(PHP_EOL, [
                    'Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name',
                    '198430,Mrs.,Serafina,I,Bumgarner,F,serafina.bumgarner@exxonmobil.com,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,212-376-9125,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner',
                ]),
                'insertions' => 1,
            ],
            [
                'csv' => implode(PHP_EOL, [
                    'Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name',
                    '324573,Mrs.,Serafina,I,Bumgarner,F,serafina.bumgarner@exxonmobil.com,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,212-376-9125,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner',
                    '324573,Mrs.,Juliette,M,Rojo,F,juliette.rojo@yahoo.co.uk,5/8/1967,06:03:23 PM,50.26,6/4/2011,6.15,215-254-9594,Glenside,Montgomery,Glenside,19038,Northeast,jmrojo',
                    '324573,Mr.,Milan,F,Krawczyk,M,milan.krawczyk@hotmail.com,4/4/1980,07:07:22 AM,37.34,1/19/2012,5.53,240-748-4111,Gibson Island,Anne Arundel,Gibson Island,21056,South,mfkrawczyk',
                    '324573,Mr.,Elmer,R,Jason,M,elmer.jason@yahoo.com,4/9/1996,12:55:59 AM,21.32,5/28/2017,0.17,236-751-5963,Mendota,Washington,Mendota,24270,South,erjason',
                    '324573,Ms.,Zelda,P,Forest,F,zelda.forest@ibm.com,11/27/1959,08:49:14 PM,57.71,1/28/2014,3.5,212-268-4076,Schenectady,Schenectady,Schenectady,12306,Northeast,zpforest',
                    '324573,Mr.,Rhett,P,Wan,M,rhett.wan@hotmail.com,7/14/1976,12:06:19 AM,41.07,1/21/2009,8.52,209-984-3789,Selma,Fresno,Selma,93662,West,rpwan',
                    '324573,Mr.,Hal,H,Farrow,M,hal.farrow@cox.net,3/15/1967,02:45:15 AM,50.41,2/25/1991,26.44,209-550-0139,Modesto,Stanislaus,Modesto,95353,West,hhfarrow',
                    '324573,Dr.,Del,I,Fernandez,M,del.fernandez@hotmail.com,8/13/1991,09:37:47 PM,25.98,4/7/2016,1.31,216-900-3642,Kent,Portage,Kent,44243,Midwest,difernandez',
                    '324573,Dr.,Corey,A,Jackman,M,corey.jackman@gmail.com,4/12/1959,12:27:16 PM,58.33,6/29/1984,33.1,212-389-8573,Jamaica,Jamaica,Jamaica,11480,Northeast,cajackman',
                    '324573,Hon.,Bibi,H,Paddock,F,bibi.paddock@yahoo.co.in,10/20/1991,10:09:34 PM,25.79,11/2/2016,0.73,423-355-3751,Rickman,Overton,Rickman,38580,South,bhpaddock',
                    '324573,Mr.,Eric,O,Manning,M,eric.manning@yahoo.com,11/2/1980,08:48:01 PM,36.76,10/28/2002,14.76,319-913-5811,Clermont,Fayette,Clermont,52135,Midwest,eomanning',
                    '324573,Ms.,Renetta,T,Hafner,F,renetta.hafner@aol.com,1/29/1975,11:50:51 AM,42.52,8/22/1998,18.95,239-664-4998,Fort Lauderdale,Broward,Fort Lauderdale,33335,South,rthafner',
                    '324573,Ms.,Paz,T,Pearman,F,paz.pearman@gmail.com,2/28/1960,08:35:18 PM,57.45,5/25/1982,35.2,319-435-3438,Garnavillo,Clayton,Garnavillo,52049,Midwest,ptpearman',
                    '324573,Hon.,Ardath,Q,Forman,F,ardath.forman@gmail.com,11/12/1982,04:54:17 AM,34.73,10/16/2009,7.79,229-447-5924,Athens,Clarke,Athens,30602,South,aqforman',
                    '953724,Mrs.,Nanci,D,Osorio,F,nanci.osorio@hotmail.com,7/9/1982,12:02:26 PM,35.08,11/7/2003,13.73,603-298-3198,Lincoln,Grafton,Lincoln,3251,Northeast,ndosorio',
                    '138700,Ms.,Maricela,H,Simard,F,maricela.simard@gmail.com,7/21/1988,07:06:17 PM,29.04,9/25/2016,0.84,252-383-7726,Ingold,Sampson,Ingold,28446,South,mhsimard',
                ]),
                'insertions' => 4, // 3 + 1 (previous data)
            ],
            [
                'csv' => implode(PHP_EOL, [
                    'Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name',
                    '198430,Mrs.,Serafina,I,Bumgarner,F,serafina.bumgarner@exxonmobil.com,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,212-376-9125,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner',
                    '178570,Mrs.,Juliette,M,Rojo,F,juliette.rojo@yahoo.co.uk,5/8/1967,06:03:23 PM,50.26,6/4/2011,6.15,215-254-9594,Glenside,Montgomery,Glenside,19038,Northeast,jmrojo',
                    '647180,Mr.,Milan,F,Krawczyk,M,milan.krawczyk@hotmail.com,4/4/1980,07:07:22 AM,37.34,1/19/2012,5.53,240-748-4111,Gibson Island,Anne Arundel,Gibson Island,21056,South,mfkrawczyk',
                    '847634,Mr.,Elmer,R,Jason,M,elmer.jason@yahoo.com,4/9/1996,12:55:59 AM,21.32,5/28/2017,0.17,236-751-5963,Mendota,Washington,Mendota,24270,South,erjason',
                    '260736,Ms.,Zelda,P,Forest,F,zelda.forest@ibm.com,11/27/1959,08:49:14 PM,57.71,1/28/2014,3.5,212-268-4076,Schenectady,Schenectady,Schenectady,12306,Northeast,zpforest',
                    '811306,Mr.,Rhett,P,Wan,M,rhett.wan@hotmail.com,7/14/1976,12:06:19 AM,41.07,1/21/2009,8.52,209-984-3789,Selma,Fresno,Selma,93662,West,rpwan',
                    '956633,Mr.,Hal,H,Farrow,M,hal.farrow@cox.net,3/15/1967,02:45:15 AM,50.41,2/25/1991,26.44,209-550-0139,Modesto,Stanislaus,Modesto,95353,West,hhfarrow',
                    '629539,Dr.,Del,I,Fernandez,M,del.fernandez@hotmail.com,8/13/1991,09:37:47 PM,25.98,4/7/2016,1.31,216-900-3642,Kent,Portage,Kent,44243,Midwest,difernandez',
                    '784160,Dr.,Corey,A,Jackman,M,corey.jackman@gmail.com,4/12/1959,12:27:16 PM,58.33,6/29/1984,33.1,212-389-8573,Jamaica,Jamaica,Jamaica,11480,Northeast,cajackman',
                    '744723,Hon.,Bibi,H,Paddock,F,bibi.paddock@yahoo.co.in,10/20/1991,10:09:34 PM,25.79,11/2/2016,0.73,423-355-3751,Rickman,Overton,Rickman,38580,South,bhpaddock',
                    '423093,Mr.,Eric,O,Manning,M,eric.manning@yahoo.com,11/2/1980,08:48:01 PM,36.76,10/28/2002,14.76,319-913-5811,Clermont,Fayette,Clermont,52135,Midwest,eomanning',
                    '207808,Ms.,Renetta,T,Hafner,F,renetta.hafner@aol.com,1/29/1975,11:50:51 AM,42.52,8/22/1998,18.95,239-664-4998,Fort Lauderdale,Broward,Fort Lauderdale,33335,South,rthafner',
                    '338634,Ms.,Paz,T,Pearman,F,paz.pearman@gmail.com,2/28/1960,08:35:18 PM,57.45,5/25/1982,35.2,319-435-3438,Garnavillo,Clayton,Garnavillo,52049,Midwest,ptpearman',
                    '324580,Hon.,Ardath,Q,Forman,F,ardath.forman@gmail.com,11/12/1982,04:54:17 AM,34.73,10/16/2009,7.79,229-447-5924,Athens,Clarke,Athens,30602,South,aqforman',
                    '953777,Mrs.,Nanci,D,Osorio,F,nanci.osorio@hotmail.com,7/9/1982,12:02:26 PM,35.08,11/7/2003,13.73,603-298-3198,Lincoln,Grafton,Lincoln,3251,Northeast,ndosorio',
                    '138780,Ms.,Maricela,H,Simard,F,maricela.simard@gmail.com,7/21/1988,07:06:17 PM,29.04,9/25/2016,0.84,252-383-7726,Ingold,Sampson,Ingold,28446,South,mhsimard',
                    '644265,Ms.,Avelina,I,Stoner,F,avelina.stoner@exxonmobil.com,10/1/1988,12:15:44 PM,28.84,11/30/2010,6.66,215-329-1990,Salina,Westmoreland,Salina,15680,Northeast,aistoner',
                    '223871,Drs.,Christene,O,Mattison,F,christene.mattison@gmail.com,9/14/1990,09:43:32 AM,26.89,9/13/2015,1.87,314-561-9256,Alba,Jasper,Alba,64830,Midwest,comattison',
                    '807262,Mr.,Stefan,O,Maeda,M,stefan.maeda@yahoo.com,3/23/1990,09:14:56 AM,27.37,11/5/2011,5.73,225-889-6869,Slidell,St. Tammany,Slidell,70461,South,somaeda',
                    '368234,Drs.,Gillian,T,Winter,F,gillian.winter@gmail.com,1/17/1960,02:25:49 PM,57.57,11/28/1984,32.68,505-325-2023,Alamogordo,Otero,Alamogordo,88310,West,gtwinter',
                    '807442,Hon.,Ed,E,Ferrari,M,ed.ferrari@gmail.com,9/27/1981,07:31:47 AM,35.86,2/15/2015,2.45,210-319-0049,Gardendale,Ector,Gardendale,79758,South,eeferrari',
                    '956778,Ms.,Jewell,L,Thies,F,jewell.thies@aol.com,2/16/1991,11:29:52 AM,26.46,4/28/2017,0.25,405-727-5191,Oklahoma City,Oklahoma City,Oklahoma City,73100,South,jlthies',
                ]),
                'insertions' => 25, // 21 + 4 (previous data)
            ],
        ];
    }
}