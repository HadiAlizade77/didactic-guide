// const redis = require('redis');

// const client = redis.createClient({
//   url: 'redis://redis:6379',
//   database: 8,
// });

// module.exports = client;
const redis = require('redis');

const client = redis.createClient({
  url: 'redis://redis:6379',
});

module.exports = client;
