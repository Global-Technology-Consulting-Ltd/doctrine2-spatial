<?php
/**
 * Copyright (C) 2015 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Spatial\Tests\DBAL\Types\Geometry;

use Doctrine\ORM\Query;
use CrEOF\Spatial\PHP\Types\Geometry\LineString;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use CrEOF\Spatial\PHP\Types\Geometry\Polygon;
use CrEOF\Spatial\PHP\Types\Geometry\MultiPolygon;
use CrEOF\Spatial\Tests\Fixtures\MultiPolygonEntity;
use CrEOF\Spatial\Tests\OrmTestCase;

/**
 * MultiPolygonType tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 *
 * @group geometry
 */
class MultiPolygonTypeTestCase extends OrmTestCase
{
    protected function setUp()
    {
        $this->usesEntity(self::MULTIPOLYGON_ENTITY);
        parent::setUp();
    }

    public function testNullMultiPolygon()
    {
        $entity = new MultiPolygonEntity();

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        $id = $entity->getId();

        $this->getEntityManager()->clear();

        $queryEntity = $this->getEntityManager()->getRepository(self::MULTIPOLYGON_ENTITY)->find($id);

        $this->assertEquals($entity, $queryEntity);
    }

    public function testMultiPolygon()
    {
        $polygons = array(
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(0, 0),
                            new Point(10, 0),
                            new Point(10, 10),
                            new Point(0, 10),
                            new Point(0, 0)
                        )
                    )
                )
            ),
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(5, 5),
                            new Point(7, 5),
                            new Point(7, 7),
                            new Point(5, 7),
                            new Point(5, 5)
                        )
                    )
                )
            )
        );
        $entity = new MultiPolygonEntity();

        $entity->setMultiPolygon(new MultiPolygon($polygons));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        $id = $entity->getId();

        $this->getEntityManager()->clear();

        $queryEntity = $this->getEntityManager()->getRepository(self::MULTIPOLYGON_ENTITY)->find($id);

        $this->assertEquals($entity, $queryEntity);
    }


    public function testFindByMultiPolygon()
    {
        $polygons = array(
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(0, 0),
                            new Point(10, 0),
                            new Point(10, 10),
                            new Point(0, 10),
                            new Point(0, 0)
                        )
                    )
                )
            ),
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(5, 5),
                            new Point(7, 5),
                            new Point(7, 7),
                            new Point(5, 7),
                            new Point(5, 5)
                        )
                    )
                )
            )
        );
        $entity = new MultiPolygonEntity();

        $entity->setMultiPolygon(new MultiPolygon($polygons));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        $result = $this->getEntityManager()->getRepository(self::MULTIPOLYGON_ENTITY)->findByMultiPolygon(new MultiPolygon($polygons));

        $this->assertEquals($entity, $result[0]);
    }
}
