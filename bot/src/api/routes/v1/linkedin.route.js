const express = require("express");
const controller = require("../../controllers/linkedin.controller");

const router = express.Router();

router.route("/extract").post(controller.extract);

module.exports = router;
