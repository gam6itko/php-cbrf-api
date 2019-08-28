<?php
namespace Tests\Gam6itko\Cbrf;

use Gam6itko\Cbrf\CreditOrgInfoService;
use Gam6itko\Cbrf\Enum\FormType;
use Gam6itko\Cbrf\Type\CoOffices;
use Gam6itko\Cbrf\Type\CreditOrg;
use Gam6itko\Cbrf\Type\CreditOrg\EnumCreditsAType;
use Gam6itko\Cbrf\Type\CreditOrgInfo;
use Gam6itko\Cbrf\Type\EnumBIC;
use Gam6itko\Cbrf\Type\F101DATA;
use Gam6itko\Cbrf\Type\IndicatorsEnum101;
use Gam6itko\Cbrf\Type\IndicatorsEnum102;
use Gam6itko\Cbrf\Type\RegionsEnum;
use Gam6itko\Cbrf\Wrapper\CreditOrgInfoWrapper;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\Cbrf\CreditOrgInfoService
 */
class CreditOrgInfoServiceTest extends TestCase
{
    private function build(): CreditOrgInfoService
    {
        $svc = new CreditOrgInfoWrapper();
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(realpath(__DIR__ . '/../metadata/'), 'Gam6itko\Cbrf\Type')
            ->build();
        return new CreditOrgInfoService($svc, $serializer);
    }

    /**
     * @covers ::getBicToIntCode()
     * @param string $bic
     */
    public function testGetBicToIntCode(string $bic = '044525225'): void
    {
        $result = $this->build()->getBicToIntCode($bic);
        self::assertNotNull($result);
    }

    /**
     * @covers ::getBicToRegNumber
     * @param string $bic
     */
    public function testBicToRegNumber(string $bic = '044525225'): void
    {
        $result = $this->build()->getBicToRegNumber($bic);
        self::assertNotNull($result);
    }

    /**
     * @param int $intCode
     */
    public function testCreditInfoByIntCode(int $intCode = 350000004): void
    {
        $result = $this->build()->creditInfoByIntCode($intCode);
        self::assertInstanceOf(CreditOrgInfo::class, $result);

        $list = $result->getCO();
        self::assertNotEmpty($list);
        self::assertInstanceOf(CreditOrgInfo\COAType::class, $list[0]);

        $list = $result->getLIC();
        self::assertNotEmpty($list);
        self::assertInstanceOf(CreditOrgInfo\LICAType::class, $list[0]);
    }

    public function testCreditInfoByIntCodeEx(): void
    {
        $array = [350000004, 450000036];
        $result = $this->build()->creditInfoByIntCodeEx($array);
        self::assertInstanceOf(CreditOrgInfo::class, $result);

        $list = $result->getCO();
        self::assertNotEmpty($list);
        self::assertInstanceOf(CreditOrgInfo\COAType::class, $list[0]);

        $list = $result->getLIC();
        self::assertNotEmpty($list);
        self::assertInstanceOf(CreditOrgInfo\LICAType::class, $list[0]);
    }

    /**
     * @param int $regionCode
     */
    public function testSearchByRegion(int $regionCode = 19): void
    {
        $result = $this->build()->searchByRegionCode($regionCode);
        self::assertInstanceOf(CreditOrg::class, $result);
        self::assertNotEmpty($result->getEnumCredits());
        self::assertInstanceOf(EnumCreditsAType::class, $result->getEnumCredits()[0]);
    }

    /**
     * @param string $name
     */
    public function testSearchByName(string $name = 'альфа'): void
    {
        $result = $this->build()->searchByName($name);
        self::assertInstanceOf(CreditOrg::class, $result);
        self::assertNotEmpty($result->getEnumCredits());
        self::assertInstanceOf(EnumCreditsAType::class, $result->getEnumCredits()[0]);
    }

    public function testData101Form(): void
    {
        $result = $this->build()->data101Form(350000004, 1, new \DateTime('2019-01-01'), new \DateTime('2019-08-01'));
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData101FormEx(): void
    {
        $result = $this->build()->data101FormEx([350000004], 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData101Full(): void
    {
        $result = $this->build()->data101Full(350000004, 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData101FullEx(): void
    {
        $result = $this->build()->data101FullEx([350000004], 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData101FullExV2(): void
    {
        $result = $this->build()->data101FullExV2([350000004], 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData101FullV2(): void
    {
        $result = $this->build()->data101FullV2(350000004, 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData102Form(): void
    {
        $result = $this->build()->data102Form(1000, 10000);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData102FormEx(): void
    {
        $result = $this->build()->data102FormEx([350000004], 1);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData123FormFull(): void
    {
        $result = $this->build()->data123FormFull(350000004);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData134FormFull(): void
    {
        $result = $this->build()->data134FormFull(350000004);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    public function testData135FormFull(): void
    {
        $result = $this->build()->data135FormFull(350000004);
        self::assertInstanceOf(F101DATA::class, $result);
    }

    //<editor-fold desc="enum">
    public function testGetDatesForForm(): void
    {
        $result = $this->build()->getDatesForForm(FormType::F101, 1481);
        self::assertIsArray($result);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetDatesForFormException(): void
    {
        $this->build()->getDatesForForm(0, 1481);
    }

    /**
     * @covers ::getLastUpdate
     * @throws \Exception
     */
    public function testLastUpdate(): void
    {
        $result = $this->build()->getLastUpdate();
        self::assertInstanceOf(\DateTime::class, $result);
    }

    public function testEnumBIC(): void
    {
        $result = $this->build()->enumBIC();
        self::assertInstanceOf(EnumBIC::class, $result);
        $list = $result->getBIC();
        self::assertNotEmpty($list);
        self::assertInstanceOf(EnumBIC\BICAType::class, $list[0]);
    }

    public function testRegionsEnum(): void
    {
        $result = $this->build()->regionsEnum();
        self::assertInstanceOf(RegionsEnum::class, $result);
        $list = $result->getRGID();
        self::assertNotEmpty($list);
        self::assertInstanceOf(RegionsEnum\RGIDAType::class, $list[0]);
    }

    /**
     * @param int $regionCode
     */
    public function testOfficesByRegion(int $regionCode = 19): void
    {
        $result = $this->build()->officesByRegion($regionCode);
        self::assertInstanceOf(CoOffices::class, $result);
        $list = $result->getOffices();
        self::assertNotEmpty($list);
        self::assertInstanceOf(CoOffices\OfficesAType::class, $list[0]);
    }

    public function testForm102IndicatorsEnum(): void
    {
        $result = $this->build()->form102IndicatorsEnum();
        self::assertInstanceOf(IndicatorsEnum102::class, $result);
        self::assertNotEmpty($result->getSIND());
        self::assertInstanceOf(IndicatorsEnum102\SINDAType::class, $result->getSIND()[0]);
    }

    public function testForm101IndicatorsEnum(): void
    {
        $result = $this->build()->form101IndicatorsEnum();
        self::assertInstanceOf(IndicatorsEnum101::class, $result);
        self::assertNotEmpty($result->getEIND());
        self::assertInstanceOf(IndicatorsEnum101\EINDAType::class, $result->getEIND()[0]);
    }
    //</editor-fold>
}