// const httpStatus = require('http-status');
const httpStatus = require('http-status');
const LI = require('../../core/handlers/extractLinkedInJobs');
/**
 * Delete user
 * @public
 */

exports.takeLastSc = async (req, res, next) => {
  const liService = new LI(req.body, res);
  let response = null;
  try {
    response = await liService.handle();
  } catch (e) {
    console.log(e);
    res
      .status(500)
      .json({
        type: 'SERVER',
        message: 'Error',
      })
      .end();
  }
  res.status(httpStatus.OK).json(response).end();
};
