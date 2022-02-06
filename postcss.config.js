const OpenProps = require('open-props');

const nested = require('postcss-nested');
const jitProps = require('postcss-jit-props');
const combineSelectors  = require('postcss-combine-duplicated-selectors');

module.exports = {
    plugins: [
        nested(),
        jitProps(OpenProps),
        combineSelectors()
    ]
}
