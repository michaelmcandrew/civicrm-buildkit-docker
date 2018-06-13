# Set up
TEST_DIR=civicrm-buildkit-docker.baseurlenvvarTest
mkdir /tmp/$TEST_DIR
cd /tmp/$TEST_DIR
git clone https://github.com/tiotsop01/civicrm-buildkit-docker
cd civicrm-buildkit-docker
git checkout tiotsop
docker-compose -p baseurlenvar up -d

# Test
docker-compose -p baseurlenvar exec -u buildkit civicrm civibuild create dmaster --patch https://github.com/civicrm/civicrm-core/pull/12307

# Tear down
docker-compose -p baseurlenvar down -v
docker volume rm -f `docker volume ls  -q --f name=baseurlenvar*`
rm -rf /tmp/$TEST_DIR
