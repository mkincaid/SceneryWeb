#!/bin/sh
#
# called by 'dlshp.psp'
#
# Supply bounding box for 'pgsql2shp' as:
#   <xmin> <ymin>,<xmax> <ymax>

UUID=${1}
PGHOST=geoscope.optiputer.net
PGUSER=webuser
PGDATABASE=landcover
PSQL="psql -h ${PGHOST} -d ${PGDATABASE} -U webuser -tA -c"
BASEDIR=/home/fgscenery
PGSQL2SHP=/opt/PostgreSQL/bin/pgsql2shp
DUMPDIR=${BASEDIR}/SHPdump/${UUID}
DLDIR=${BASEDIR}/SHPdl

LayerSelect() {
  ${PSQL} "SELECT pgislayer FROM download \
    WHERE uuid LIKE '${UUID}'"
}

PGISLAYER=`LayerSelect`

GeomSelect() {
  ${PSQL} "SELECT ST_AsText(${1}_geometry) FROM download \
    WHERE uuid LIKE '${UUID}'" | cut -f 2 -d \( | cut -f 1 -d \)
}

LL_GEOMETRY=`GeomSelect ll`
UR_GEOMETRY=`GeomSelect ur`
BBOX="${LL_GEOMETRY}, ${UR_GEOMETRY}"

mkdir -p ${DUMPDIR} && cd ${DUMPDIR}/ || exit 1
rm -f *

for LAYER in `${PSQL} "SELECT f_table_name FROM geometry_columns \
        WHERE f_table_name LIKE '${PGISLAYER}\_%' \
        AND (type LIKE 'ST_Polygon' OR type LIKE 'POLYGON') \
        ORDER BY f_table_name"`; do
    COUNT=`${PSQL} "SELECT COUNT(wkb_geometry) FROM ${LAYER} \
              WHERE wkb_geometry && \
              ST_SetSRID('BOX3D(${BBOX})'::BOX3D, 4326)"`
    if [ ${COUNT} -gt 0 ]; then
        ${PGSQL2SHP} -f ${DUMPDIR}/${LAYER}.shp \
            -h ${PGHOST} -u ${PGUSER} -g wkb_geometry -b -r ${PGDATABASE} \
            "SELECT * FROM ${LAYER} \
                WHERE wkb_geometry && \
                ST_SetSRID('BOX3D(${BBOX})'::BOX3D, 4326)"
        cp -a ${BASEDIR}/landcover/EPSG4326.prj ${DUMPDIR}/${LAYER}\.prj
    fi
done

cp -a ${BASEDIR}/landcover/COPYING.gplv2 ${DUMPDIR}/COPYING

zip ${DLDIR}/${UUID}\.zip *
cd ${DUMPDIR}/.. && rm -rf ${UUID}

# EOF