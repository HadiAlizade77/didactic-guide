/* eslint-disable no-await-in-loop */
// const initPuppeteer = require('../chromium/initChrome');
const puppeteer = require("puppeteer");
const url = require("url");
const fs = require("fs");

const u = "yesep45890@kaudat.com";
const p = "9147071008";
const keyword = "developer";
const country = "developer";
// module.exports = class LinkedInHandler {
// constructor(reqBody, res) {}
const path = require("path");

async function handle() {
  // Create a browser instance
  const browser = await puppeteer.launch({
    headless: false,
    // devtools: true,
    // userDataDir: './cache',
    ignoreDefaultArgs: ["--enable-automation"],
    // executablePath: '/usr/bin/chromium-browser',
    args: ["--disable-setuid-sandbox", "--no-sandbox", "--no-zygote"],
  });
  const shareBox = ".global-nav__a11y-menu";

  // Create a new page
  const page = await browser.newPage();
  await page.setRequestInterception(true);
  page.on("request", (request) => {
    if (
      false
      // ["image", "font"].indexOf(request.resourceType()) !== -1
      // ["image", "stylesheet", "font"].indexOf(request.resourceType()) !== -1 ||
      // request.url().includes("/track")
    ) {
      request.abort();
    } else {
      request.continue();
    }
  });
  // Set viewport width and height
  await page.setViewport({ width: 1200, height: 720 });

  const website_url = "https://www.linkedin.com/login";

  // Open URL in current page
  await page.goto(website_url, { waitUntil: "networkidle0" });

  // // login username
  if ((await page.$("#username")) !== null) {
    await page.waitForSelector("#username");
    await page.type("#username", u);
  }

  let counter0 = 0;
  let counter1 = 0;
  // login pass
  await page.waitForSelector("#password");
  await page.type("#password", p);

  // login click
  let lgnBtn = "";
  if ((await page.$("#username")) !== null) {
    lgnBtn = "#organic-div > form > div.login__form_action_container > button";
  } else {
    lgnBtn =
      "#fastrack-div > div.mt-12 > form > div.login__form_action_container > button";
  }
  await page.waitForSelector(lgnBtn);
  await page.click(lgnBtn);

  await page.waitForSelector(shareBox);
  console.log("login success");
  const parsedUrl = url.parse("https://www.linkedin.com/jobs/search/", true);
  parsedUrl.query.f_WT = "2";
  parsedUrl.query.baz = "another-new-value";
  const modifiedUrlString = url.format(parsedUrl);
  await page.goto(modifiedUrlString);
  await page.waitForSelector(".jobs-search-box__text-input");
  // keyword
  // await page.waitForSelector('.jobs-search-box__text-input jobs-search-box__keyboard-text-input');
  // await page.type('.jobs-search-box__text-input jobs-search-box__keyboard-text-input', keyword);
  // country
  // await page.waitForSelector(".jobs-search-box__text-input");
  // await page.type(".jobs-search-box__text-input", keyword);
  // await page.keyboard.press("Enter");

  console.log("found");
  // await page.waitForNavigation();

  const found = await page.waitForSelector("div.jobs-search-results-list > ul");

  console.log(found);

  await new Promise((r) => setTimeout(r, 3000));

  try {
    const filtersAll = await page.$(
      "#search-reusables__filters-bar > div > div > button"
    );

    console.log("clollllo");
    await filtersAll.click();
    await page.waitForSelector(
      ".reusable-search-filters-advanced-filters__add-filter-button"
    );
  } catch (e) {
    console.log("failed clicking all filter");
  }
  // items
  //[
  //"Add a company",
  //"Add an industry",
  // "Add a job function"
  ///]
  //  const clickAddNewIndustryBtn =

  try {
    await new Promise((r) => setTimeout(r, 2000));

    try {
      console.log("click ind btn");

      const [button] = await page.$x("//span[contains(., 'Add an industry')]");
      if (button) {
        await button.click();
      }
    } catch (e) {
      console.log("failed add filter");
    }
    const industryValue = "Banking";

    console.log("click ind input");

    const [input] = await page.$x("//input[@*='Add an industry']");
    await input.type(industryValue);
    try {
      console.log("search inds");
      await new Promise((r) => setTimeout(r, 1000));
      const [el] = await page.$x(`//*[text()='${industryValue}']`);
      await el.click();
      console.log("selected inds");
    } catch (e) {
      console.log("failed selecting");
    }

    try {
      console.log("search for btn");

      const [el] = await page.$x(
        `//*[@data-test-reusables-filters-modal-show-results-button]`
      );
      console.log(el);
      await el.click();

      await page.waitForNavigation();
    } catch (e) {
      console.log("failed change show result");
    }
  } catch (e) {
    console.log(e, "failed typing");
  }
  const jobs = {};

  // Define the file path and file name
  const filePath = path.join(__dirname, "data.json");

  // Chec
  try {
    page.on("response", async (response) => {
      if (
        response.url().includes("voyager/api/jobs/jobPostings") &&
        response.ok() &&
        response.request().method() === "GET"
      ) {
        const responseJson = await response.json();
        jobData = responseJson;
        console.log(jobData.data.formattedLocation);
        console.log({ counter0, counter1 }, "firest");
        if (jobData) {
          counter0++;
          console.log(jobData.data.jobPostingId);

          jobs[jobData.data.jobPostingId] = {
            // title,
            jobDesc: jobData.data.description.text,
            employmentStatus: jobData.data.formattedEmploymentStatus,
            experienceLevel: jobData.data.formattedExperienceLevel,
            location: jobData.data.formattedLocation,
            applyUrl:
              jobData.data.applyMethod?.companyApplyUrl ??
              jobData.data.applyMethod?.easyApplyUrl ??
              "",
          };
          if (fs.existsSync(filePath)) {
            // Read the file contents
            const fileContents = fs.readFileSync(filePath, "utf-8");

            // Parse the JSON contents
            const jsonArray = JSON.parse(fileContents);

            // Append the new JSON object to the array
            jsonArray.push(jobs[jobData.data.jobPostingId]);

            // Write the updated array to the file
            fs.writeFileSync(filePath, JSON.stringify(jsonArray));
          } else {
            // Create the file and write the new JSON object as an array
            fs.writeFileSync(
              filePath,
              JSON.stringify([jobs[jobData.data.jobPostingId]])
            );
          }
        }
      }
    });
  } catch (e) {
    console.log(e);
    console.log("err getting job data");
  }
  await new Promise((r) => setTimeout(r, 2000));

  await new Promise((r) => setTimeout(r, 1000));
  await new Promise((r) => setTimeout(r, 1000));

  await new Promise((r) => setTimeout(r, 2000));

  // const [xxxxxxxxxxx] = await page.$x(`//*[@data-test-modal-close-btn]`);
  // await xxxxxxxxxxx.click();
  await new Promise((r) => setTimeout(r, 4000));

  // console.log({ xxxxxxxxxxx });
  let currentPage = 1;
  const pageCount = await page.$eval(
    "ul.artdeco-pagination__pages",
    // eslint-disable-next-line no-bitwise
    (option) =>
      ~~Array.from(option.children)[Array.from(option.children).length - 1]
        .innerText
  );
  console.log({ pageCount });
  console.log(currentPage <= pageCount);
  while (currentPage <= pageCount) {
    const jobElIds = await page.$eval(
      "div.jobs-search-results-list > ul",
      (option) =>
        Array.from(option.children).map(
          (child) => `#${child.getAttribute("id")}`
        )
    );
    const listItems = await page.$$("div.jobs-search-results-list > ul > li");

    // Click on each element in the array
    // for (let i = 0; i < listItems.length; i++) {
    //   await listItems[i].click();
    // }

    console.log(jobElIds);
    currentPage += 1;
    // eslint-disable-next-line no-restricted-syntax
    for (const elId of listItems) {
      counter1++;

      // await new Promise((r) => setTimeout(r, 100000));
      // await page.waitForSelector(elId);
      let jobData = null;
      console.log({ counter0, counter1 }, "sec");
      await elId.hover();
      await new Promise((r) => setTimeout(r, 500));

      try {
        const clickable = await elId.$(
          ".mr1.job-card-list__logo.artdeco-entity-lockup__image.artdeco-entity-lockup__image--type-square.ember-view"
        );

        await clickable.click();
        const currentUrl = page.url();
        const currentJob = url.parse(currentUrl, true);

        await page.waitForFunction(
          () => !document.querySelector(".jobs-ghost-fadein-placeholder")
        );
        // const titleEl = await page.waitForSelector(
        //   ".jobs-unified-top-card__job-title"
        // ); // select the element
        // const title = await titleEl.evaluate((el) => el.textContent); // grab the textContent from the element, by evaluating this function in the browser context
        console.log("change job");
        if (!jobs.hasOwnProperty(currentJob)) {
          await new Promise((r) => setTimeout(r, 500));
        }
      } catch (e) {
        console.log(e);
        console.log("node removed");
      }
    }

    await page.$eval(`[data-test-pagination-page-btn="${currentPage}"]`, (el) =>
      el.children[0].click()
    );
  }

  // console.log(jobs);
  // await new Promise((r) => setTimeout(r, 3000));

  // You will now have an array of strings
  // Capture screenshot

  // Close the browser instance
  await browser.close();
}
// };

//type

// const pastMonth = "f_TPR=r2592000";
// const On_site = "&f_WT=1";
// const remoteOnly = "&f_WT=2";
// const hybvrid = "&f_WT=3";

// const fullTime = "f_JT=F";
// const partTime = "f_JT=P";
// const contract = "f_JT=C";
// const tempJob = "f_JT=T";
// const volunteer = "f_JT=V";
// const internship = "f_JT=I";
// const other = "f_JT=O";
// //

// const Internship = "f_E=1";
// const Associate = "f_E=3";
// const Mid_Senior = "f_E=4";
// const Director = "f_E=5";
// const Executive = "f_E=6";
handle();
//experience
