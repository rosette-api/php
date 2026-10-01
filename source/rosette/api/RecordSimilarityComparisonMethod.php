<?php

/**
 * @copyright 2026 Babel Street Rosette Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * @license http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 **/

namespace rosette\api;

/**
 * Class that represents the Record Similarity Comparison Method.
 */
enum RecordSimilarityComparisonMethod: string
{
    case ONE_TO_ONE = 'one_to_one';
    case ONE_TO_N = 'one_to_n';
    case N_TO_M = 'n_to_m';
}
