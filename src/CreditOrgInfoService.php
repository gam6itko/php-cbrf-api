<?php

namespace Gam6itko\Cbrf;

use Gam6itko\Cbrf\Model\CoOffices;
use Gam6itko\Cbrf\Model\CreditOrg;
use Gam6itko\Cbrf\Model\CreditOrgInfo;
use Gam6itko\Cbrf\Model\EnumBIC;
use Gam6itko\Cbrf\Model\F101DATA;
use Gam6itko\Cbrf\Model\IndicatorsEnum101;
use Gam6itko\Cbrf\Model\IndicatorsEnum102;
use Gam6itko\Cbrf\Model\RegionsEnum;
use Gam6itko\Cbrf\Wrapper\CreditOrgInfoWrapper;
use Gam6itko\Cbrf\Enum\FormType;
use JMS\Serializer\SerializerInterface;

/**
 * @method CreditOrgInfo     creditInfoByIntCode(float $internalCode)                                                                                                        Информация о кредитной орг. по вн.коду
 * @method CreditOrgInfo     creditInfoByIntCodeEx(float[] $internalCode)                                                                                                    Информация о кредитной орг. по вн.коду
 * @method F101DATA          data101Form(int $credorgNumber, int $indId, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)
 * @method F101DATA          data101FormEx(int[] $credorgNumbers, int $indId, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)
 * @method F101DATA          data101Full(int $credorgNumber, int $indCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)
 * @method F101DATA          data101FullEx(int[] $credorgNumbers, int $indCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)
 * @method F101DATA          data101FullExV2(int[] $credorgNumbers, int $indCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)
 * @method F101DATA          data101FullV2(int $credorgNumber, int $indCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)                         Данные КО. формы 101
 * @method F101DATA          data102Form(int $credorgNumber, int $symbCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)                          Данные КО. формы 102, кратко
 * @method F101DATA          data102FormEx(int[] $credorgNumber, int $symbCode, \DateTimeInterface $dateFrom = null, \DateTimeInterface $dateTo = null)                      Данные КО. формы 102, кратко по нескольким КО.
 * @method F101DATA          data123FormFull(int $credorgNumber, \DateTimeInterface $onDate = null)                                                                          Данные по форме 123
 * @method F101DATA          data134FormFull(int $credorgNumber, \DateTimeInterface $onDate = null)                                                                          Данные по форме 134
 * @method F101DATA          data135FormFull(int $credorgNumber, \DateTimeInterface $onDate = null)                                                                          Данные по форме 135
 * @method EnumBIC           enumBIC()                                                                                                                                       Данные по BIC кодам КО, без филиалов
 * @method IndicatorsEnum101 form101IndicatorsEnum()                                                                                                                         Справочник индикаторов для формы 101
 * @method IndicatorsEnum102 form102IndicatorsEnum()                                                                                                                         Справочник символов для формы 102
 * @method RegionsEnum       regionsEnum()                                                                                                                                   Справочник регионов
 * @method CoOffices         officesByRegion(int $regCode) Информация по филиальной сети кредитной орг. по региону (коду)
 * @method CreditOrg         searchByRegionCode(string $regionCode) Поиск кредитных орг. по региону(коду)
 * @method CreditOrg         searchByName(string $name)                                                                                                                      Поиск кредитных орг. по названию
 */
class CreditOrgInfoService
{
    /** @var CreditOrgInfoWrapper */
    private $svc;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CreditOrgInfo constructor.
     *
     * @param $serializer
     * @param $svc
     */
    public function __construct(CreditOrgInfoWrapper $svc, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->svc = $svc;
    }

    /**
     * @return CreditOrgInfoWrapper
     */
    public function getSvc(): CreditOrgInfoWrapper
    {
        return $this->svc;
    }

    public function getDatesForForm(int $formNumber, int $credPrgNumber)
    {
        if (!in_array($formNumber, FormType::getNumbers())) {
            throw new \LogicException('Not supported form number');
        }

        $methodName = "GetDatesForF{$formNumber}";
        $result = call_user_func([$this->svc, $methodName], $credPrgNumber);

        return $result['dateTime'];
    }

    /**
     * Получение даты последнего обновления базы по КО.
     *
     * @return \DateTime
     *
     * @throws \Exception
     */
    public function getLastUpdate(): \DateTime
    {
        $dt = preg_replace('/\.\d*/', '', $this->svc->LastUpdate());

        return new \DateTime($dt);
    }

    /**
     * Bic код во внутренний код кред.орг.
     *
     * @param string $bicCode
     *
     * @return int
     */
    public function getBicToIntCode(string $bicCode)
    {
        return (int) $this->svc->BicToIntCode($bicCode);
    }

    /**
     * Bic код в регистрационный номер кред.орг.
     *
     * @param string $bicCode
     *
     * @return int
     */
    public function getBicToRegNumber(string $bicCode)
    {
        return $this->svc->BicToRegNumber($bicCode);
    }

    public function __call(string $name, array $arguments = [])
    {
        $methodName = $this->getTargetMethod($name) ?? (ucfirst($name).'XML');
        $res = call_user_func_array([$this->svc, $methodName], $arguments);
        if (empty($res)) {
            return null;
        }

        return $this->serializer->deserialize($this->extractXmlString($res), $this->getResultClass($name), 'xml');
    }

    protected function extractXmlString(array $res): string
    {
        $result = str_replace('xmlns=""', '', trim($res['any']));
        if ('<diffgr:diffgr' === substr($result, 0, 14)) {
            $result = preg_replace('/<diffgr:diffgram.*?>(.*?)<\/diffgr:diffgram>/', '$1', $result);
        }

        return $result;
    }

    protected function getTargetMethod(string $name): ?string
    {
        $mapping = [
            'officesByRegion' => 'GetOfficesByRegion',
            'enumBIC' => 'EnumBIC_XML',
        ];

        return $mapping[$name] ?? null;
    }

    protected function getResultClass(string $name): ?string
    {
        $mapping = [
            'creditInfoByIntCode' => CreditOrgInfo::class,
            'creditInfoByIntCodeEx' => CreditOrgInfo::class,
            'data101Form' => F101DATA::class,
            'data101FormEx' => F101DATA::class,
            'data101Full' => F101DATA::class,
            'data101FullEx' => F101DATA::class,
            'data101FullExV2' => F101DATA::class,
            'data101FullV2' => F101DATA::class,
            'data102Form' => F101DATA::class,
            'data102FormEx' => F101DATA::class,
            'data123FormFull' => F101DATA::class,
            'data134FormFull' => F101DATA::class,
            'data135FormFull' => F101DATA::class,
            'enumBIC' => EnumBIC::class,
            'form101IndicatorsEnum' => IndicatorsEnum101::class,
            'form102IndicatorsEnum' => IndicatorsEnum102::class,
            'officesByRegion' => CoOffices::class,
            'regionsEnum' => RegionsEnum::class,
            'searchByRegionCode' => CreditOrg::class,
            'searchByName' => CreditOrg::class,
        ];

        return $mapping[$name] ?? null;
    }
}
