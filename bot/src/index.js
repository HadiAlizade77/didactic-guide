// make bluebird default Promise
Promise = require('bluebird'); // eslint-disable-line no-global-assign
const { port, env } = require('./config/vars');
const logger = require('./config/logger');
const app = require('./config/express');
const redis = require('./config/redis');
// open mongoose connection

// listen to requests
app.listen(port, () => logger.info(`server started on port ${port} (${env})`));

(async () => {
  await redis.connect();
})();

/**
 * Exports express
 * @public
 */
module.exports = app;
