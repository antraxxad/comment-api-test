/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
// start the Stimulus application
import { Tooltip, Toast, Popover } from 'bootstrap';
import './bootstrap';

// require jQuery normally
const $ = require('jquery');
// create global $ and jQuery variable
global.$ = global.jQuery = $;

const commentContainer = document.querySelector('.js-comment-wrapper');
const page = commentContainer.dataset.currentPage;

let commentBlock = function (comment, pageName, user = null) {
    const id = comment.id;
    const author = comment.author;
    const message = comment.message;
    const rate = comment.rate;
    const createdAt = comment.createdAt;
    const responses = comment.commentResponses
    let commentBlock = `<div id="comment-{id}" class='comment' data-comment-id="{id}" data-author="{author}" data-created-at="{createdAt}">
                            <p>
                                {message}<br>
                                <span>Author: {author}</span>, <span>Rate: {rate}</span>
                            </p>
                            <div id="response-wrapper-{id}" class="response-wrapper"></div>
                        </div>`;
    return commentBlock;
}

let responseBlock = function (response, user = null) {
    const id = response.id;
    const author = response.author;
    const message = response.message;
    const createdAt = response.createdAt;
    let responseBlock = `<div class='response' data-response-id="{id}" data-author="{author}" data-created-at="{createdAt}">
                            <p>
                                {message}<br>
                                <span>Author: {author}</span>, <span>Rate: {rate}</span>
                            </p>
                        </div>`;
    return responseBlock;
}

let commentForm = function (currentComment = null) {
    const id = (currentComment) ? currentComment.id : null;
    const message = (currentComment) ? currentComment.message : null;
    const rate = (currentComment) ? currentComment.rate: null;
    let formBlock = `<div class='commentForm' data-author="{author}" data-created-at="{createdAt}">
                            <form>
                                <div class="form-group">
                                    <label for="rate">Email address</label>
                                    <input type="number" class="form-control" id="rate" name="rate" value="{rate}">
                                </div>
                                <div class="form-group">
                                    <label for="message">Example textarea</label>
                                    <textarea class="form-control" id="message" name="message" rows="3">{message}</textarea>
                                </div>
                                <input name="id" value="{id}" hidden>
                            </form>
                    </div>`;
    return formBlock;
}

let responseForm = function (comment) {
    let formBlock = `<div class='responseForm'>
                            <form>
                                <div class="form-group">
                                    <label for="rate">Email address</label>
                                    <input type="number" class="form-control" id="rate" name="rate" value="{rate}">
                                </div>
                                <div class="form-group">
                                    <label for="message">Example textarea</label>
                                    <textarea class="form-control" id="message" name="message" rows="3">{message}</textarea>
                                </div>
                                <input name="comment" value="{comment}" hidden>
                            </form>
                    </div>`;
    return formBlock;
}

let apiCallTest = async function (url, options = {"method":"get", "data":{}}) {
    fetch(url, [options])

    if (response.ok) {
        let json = await response.json();
    } else {
        alert("HTTP-Error: " + response.status);
    }
}

async function apiCall(url) {
    let dataSet = 'pending...';
    let call = fetch(`${url}`).then(
        successResponse => {
            if (successResponse.status != 200) {
                return null;
            } else {
                let response = successResponse.body;
                return successResponse.json(response);
            }
        },
        failResponse => {
            return null;
        }
    ).then(data=>{ return data; });

    await Promise.call;
}

console.log(apiCall('http://localhost:8000/comment/'+page));
/*****************************************************************/
const apiService = {
    call: function (method, path, params = []) {
        if ('GET' === method) {
            return this.get(path, params);
        }

        if ('POST' === method) {
            return this.post(path, params);
        }

        throw new Error(`Unsupported method "${method}"`);
    },

    get: function (path, params) {
        // If doesn't require any parameters, just send it
        if (0 === params.length) {
            return axios.get(path);
        }
        // Adding "?" because it's the first parameter
        path += '?';

        params.forEach((param) => {
            // If parameter is required and its value is not provided
            if (param.isRequired && null === param.value) {
                throw new Error(`No value provided for required param "${param.name}"`);
            }

            // If optional parameter is not provided
            if ('' === param.value) {
                return;
            }

            // If this isn't the first parameter
            if ('?' !== path.slice(-1)) {
                path += '&';
            }

            path += `${param.name}=${param.value}`;

        });

        return axios.get(path);
    },

    post: function (path, params) {
        // If doesn't require any parameters, just send it
        if (0 === params.length) {
            return axios.post(path);
        }

        const data = {};

        params.forEach((param) => {
            // If parameter is required and its value is not provided
            if (param.isRequired && null === param.value) {
                throw new Error(`No value provided for required param "${param.name}"`);
            }

            // If optional parameter is not provided
            if ('' === param.value) {
                return;
            }

            data[param.name] = param.value;
        });

        return axios.post(path, data);
    }
};