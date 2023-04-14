/* eslint-disable prefer-destructuring */
const puppeteerAfp = require('puppeteer-afp');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
const useProxy = require('puppeteer-page-proxy');
const db = require('../../config/db');
const events = require('../events');

const UA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.3904.108 Safari/537.36';

puppeteer.use(StealthPlugin());

async function launchNewInstance(payload) {
  const username = payload.username;
  const proxy = payload.proxy;
  console.log('lunching new instance');
  const browser = await puppeteer.launch({
    headless: !false,
    // devtools: true,
    userDataDir: `./cache/${username}`,
    ignoreDefaultArgs: ['--enable-automation'],
    executablePath: '/usr/bin/chromium-browser',
    args: [
      '--disable-setuid-sandbox',
      '--no-sandbox',
      '--no-zygote'],
  });
  const [pageFP] = await browser.pages();
  await pageFP.setJavaScriptEnabled(true);
  await pageFP.setUserAgent(UA);
  const page = puppeteerAfp(pageFP);
  await page.setRequestInterception(true);
  await page.setViewport({ width: 1280, height: 800 });
  if (proxy) {
    console.log(`${proxy.protocol}${proxy.username}${proxy.password ? `${proxy.password}:` : ''}@${proxy.ip}:${proxy.port}`);
    await useProxy(page, `${proxy.protocol}${proxy.username}${proxy.password ? `${proxy.password}:` : ''}@${proxy.ip}:${proxy.port}`);
  }
  await db.ws.setWsAddress(username, browser.wsEndpoint());
  console.log(await page.evaluate('navigator.userAgent'));
  page.on('request', (request) => {
    request.continue();
  });
  await page.setRequestInterception(true);

  return { page, browser };
}

async function connectToExistingBrowser({ username, chromiumInstance }) {
  console.log('connect to old instance');
  let browser = null;
  let page = null;
  try {
    browser = await puppeteer.connect({ browserWSEndpoint: chromiumInstance });
    [page] = await browser.pages();
  } catch (e) {
    await events.browserReconnectFailed();
    const newInstance = await launchNewInstance({ username });
    browser = newInstance.browser;
    page = newInstance.page;
  }
  return { page, browser };
}

async function initPuppeteer({ username }) {
  try {
    const chromiumInstance = await db.ws.getWsAddress(username);
    let browser = null;
    let page = null;

    if (chromiumInstance) {
      const existingInstance = await connectToExistingBrowser({ username, chromiumInstance });
      browser = existingInstance.browser;
      page = existingInstance.page;
      // not sure tf im doing rn
      await browser.close();
      const newInstance = await launchNewInstance({ username });
      browser = newInstance.browser;
      page = newInstance.page;
    } else {
      const newInstance = await launchNewInstance({ username });
      browser = newInstance.browser;
      page = newInstance.page;
    }

    return { page, browser };
  } catch (e) {
    console.error(e);
    console.error('Error lunching puppeteer');
    throw e;
  }
}
async function removeProxy(page) {
  return useProxy(page, null);
}

module.exports = { initPuppeteer, launchNewInstance, removeProxy };
