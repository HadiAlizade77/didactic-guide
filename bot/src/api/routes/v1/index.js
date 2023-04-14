const express = require('express');

const linkedinRoutes = require('./linkedin.route');

const router = express.Router();

/**
 * GET v1/status
 */
router.get('/status', (req, res) => res.send('OK'));
// router.use("/shots", express.static("shots"));
/**
 * GET v1/docs
 */
// router.use("/docs", express.static("docs"));

// router.use('/users', userRoutes);
router.use('/li', linkedinRoutes);

module.exports = router;
