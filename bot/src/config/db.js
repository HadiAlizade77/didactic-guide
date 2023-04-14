/* eslint-disable class-methods-use-this */
const redis = require('./redis');

const wsPrefix = 'BOT_WS_';
const statusPrefix = 'BOT_STATUS_';
const status = {
  // key is user's email
  getSniping: () => redis.get(`${statusPrefix}-sniping`) === 'ACTIVE',
  // true for running and false for stopped
  setSniping: async (value) => {
    const strValue = value ? 'ACTIVE' : 'DEACTIVATED';
    redis.set(`${statusPrefix}-sniping`, strValue, {
      EX: 120,
    });
  },

};

const ws = {
  // key is user's email
  getWsAddress: (value) => redis.get(`${wsPrefix}${value}`),
  // true for running and false for stopped
  setWsAddress: async (key, value) => {
    await redis.set(`shadow:${wsPrefix}${key}`, value, {
      EX: 1750,
    });
    return redis.set(`${wsPrefix}${key}`, value, {
      EX: 1800,
    });
  },
  renewWsAddressTTL: async (key) => {
    await redis.sendCommand(['EXPIRE', `shadow:${wsPrefix}${key}`, 1750]);
    return redis.sendCommand(['EXPIRE', key, 1800]);
  },
  removeWsData: async (key) => {
    await redis.del(`shadow:${wsPrefix}${key}`);
    return redis.del(`shadow:${wsPrefix}${key}`);
  },
};

const navStatusPrefix = 'BOT_NAV_';
const nav = {
  // key is ws address
  getNavStatus: (value) => redis.get(`${navStatusPrefix}${value}`),
  // true for running and false for stopped
  setNavAddress: async (key, value) => {
    await redis.set(`shadow:${navStatusPrefix}${key}`, value, {
      EX: 1750,
    });
    return redis.set(`${navStatusPrefix}${key}`, value, {
      EX: 1800,
    });
  },
  renewNavAddressTTL: async (key) => {
    await redis.sendCommand(['EXPIRE', `shadow:${navStatusPrefix}${key}`, 1750]);
    return redis.sendCommand(['EXPIRE', key, 1800]);
  },
  removeNavData: async (key) => {
    await redis.del(`shadow:${navStatusPrefix}${key}`);
    return redis.del(`${navStatusPrefix}${key}`);
  },
};

module.exports = {
  ws, nav, status,
};
