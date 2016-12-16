<?php
/**
 * Api.
 *
 * Primary class for interfacing with the Rosette API
 *
 * @copyright 2015-2016 Basis Technology Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * @license   Apache http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 **/
namespace rosette\api;

/*
 * Per PSR: A file SHOULD declare new symbols (classes, functions, constants, etc.) and cause no other
 * side effects, or it SHOULD execute logic with side effects, but SHOULD NOT do both.
 *
 * To accommodate this, we perform the require_once in its own file
 */
require_once __DIR__ . '/../../../vendor/autoload.php';
