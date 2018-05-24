<?php
namespace DataLogger\RestApp\Chart;

class CommonChartModel extends CategorizedChartModel
{
    private $requireTransaction=['addCategory'];
    protected $seriesModel,$categoriesModel,$pdo, $accountsId, $properties;

    public function __construct(ChartCollectionModel $seriesModel, ChartCollectionModel $categoriesModel, \Pdo $pdo, int $accountsId, array $properties) {
        $this->seriesModel=$seriesModel;
        $this->categoriesModel=$categoriesModel;
        $this->pdo=$pdo;
        $this->accountsId=$accountsId;
        $this->properties=$properties;
    }

    public function read() {
        /* All chart table data is already in this->properties, so maybe don't include this table in the query,
        but since charts_common currently has no columns (other than ID), there is no single table to join to */
        $sql=<<<EOT
            SELECT c.id, c.idPublic, c.name, c.config,
            cth.name theme, cth.id themeId, cty.type, cty.name typeName, cty.masterType, cth.config configDefault,
            ccc.idPublic categoriesId, ccc.name categoriesName, ccs.idPublic seriesId, ccs.name seriesName,
            p.idPublic pointsId, p.name pointsName, p.units
            FROM charts c
            INNER JOIN chart_themes cth ON cth.id=c.themesId
            INNER JOIN chart_types cty ON cty.type=cth.type
            LEFT OUTER JOIN charts_common_series ccs ON ccs.chartsCommonId=c.id
            LEFT OUTER JOIN charts_common_categories ccc ON ccc.chartsCommonId=c.id
            LEFT OUTER JOIN charts_common_has_points cchp ON cchp.chartsCommonCategoriesId=ccc.id AND cchp.chartsCommonSeriesId=ccs.id
            LEFT OUTER JOIN points p ON p.id=cchp.pointsId
            WHERE ccs.chartsCommonId=?
            ORDER BY ccs.position ASC, ccc.position ASC
EOT;
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute([$this->properties['id']]);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $series=[];
        $categories=[];
        foreach($stmt as $rs) {
            //syslog(LOG_INFO,'$rs: '.json_encode($rs));
            if(empty($data)) {
                $data=$rs;
            }
            if($rs['seriesId'] && !isset($series[$rs['seriesId']])) {
                $series[$rs['seriesId']]=['id'=>$rs['seriesId'], 'name'=>$rs['seriesName'], 'data'=>[]];
            }
            if($rs['categoriesId'] && !isset($categories[$rs['categoriesId']])) {
                $categories[$rs['categoriesId']]=new CategoryNode(['id'=>$rs['categoriesId'], 'name'=>$rs['categoriesName']]);
            }
            $series[$rs['seriesId']]['data'][]=new PointNode(['id'=>$rs['pointsId'], 'name'=>$rs['pointsName'], 'units'=>$rs['units']]);
        }
        $categories=array_values($categories);
        $series=array_values($series);
        foreach($series as &$s) {
            $serie=new SeriesNode($s);
        }
        //syslog(LOG_INFO,'$chart: '.json_encode($chart));
        $chart=new CommonChartEntity(new SeriesCollection($series), new CategoryCollection($categories), $data);
        return $chart;
    }

}