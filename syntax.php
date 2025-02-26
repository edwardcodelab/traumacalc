<?php

 /**
 * traumacalc: a Trauma Score Calculator (AIS, ISS, RTS, TRISS) in DokuWiki
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author dodotori@dokuwikiforum <dodotori@dokuwikiforum>
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_traumacalc extends DokuWiki_Syntax_Plugin {
    function getType() {
        return 'substition';
    }

    function getPType() {
        return 'block';
    }

    function getSort() {
        return 157;
    }

    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<traumacalc>', $mode, 'plugin_traumacalc');
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {
        return array();
    }

    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;


  //      $renderer->doc .= '<script type="text/javascript" src="' . DOKU_BASE . 'lib/plugins/traumacalc/script.js"></script>';

        // HTML for the calculator with prefixed IDs and coefficient selection
        $html = '
        <div class="traumacalc">
            <h1>Trauma Score Calculator</h1>

            <!-- ISS Section -->
            <div class="section">
                <h2>Injury Severity Score (ISS)</h2>
                <label for="traumacalc_head">Head:</label>
                <select id="traumacalc_head">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_face">Face:</label>
                <select id="traumacalc_face">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_chest">Chest:</label>
                <select id="traumacalc_chest">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_abdomen">Abdomen:</label>
                <select id="traumacalc_abdomen">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_extremity">Extremity:</label>
                <select id="traumacalc_extremity">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_external">External:</label>
                <select id="traumacalc_external">
                    <option value="0">0: No injury</option>
                    <option value="1">1: Minor (e.g., superficial laceration, mild contusion)</option>
                    <option value="2">2: Moderate (e.g., simple fracture, moderate laceration)</option>
                    <option value="3">3: Serious (e.g., open fracture, significant organ injury)</option>
                    <option value="4">4: Severe (e.g., major vessel laceration, substantial blood loss)</option>
                    <option value="5">5: Critical (e.g., massive hemorrhage, severe brain injury)</option>
                    <option value="6">6: Maximum/Untreatable (e.g., decapitation, crush of vital organs)</option>
                </select><br>
                <label for="traumacalc_iss">ISS:</label>
                <input type="text" id="traumacalc_iss" readonly>
            </div>

            <!-- RTS Section -->
            <div class="section">
                <h2>Revised Trauma Score (RTS)</h2>
                <label for="traumacalc_sbp">Systolic BP:</label>
                <input type="text" id="traumacalc_sbp" placeholder="0-299"><br>
                <label for="traumacalc_rr">Resp. Rate:</label>
                <input type="text" id="traumacalc_rr" placeholder="0-80"><br>
                <label for="traumacalc_gcs">GCS:</label>
                <input type="text" id="traumacalc_gcs" placeholder="3-15"><br>
                <label for="traumacalc_rts">RTS:</label>
                <input type="text" id="traumacalc_rts" readonly>
            </div>

            <!-- TRISS Section -->
            <div class="section">
                <h2>TRISS</h2>
                <label for="traumacalc_age">Age:</label>
                <input type="text" id="traumacalc_age" placeholder="0-120"><br>

                <label for="traumacalc_trissBlunt">Blunt:</label>
                <input type="text" id="traumacalc_trissBlunt" readonly><br>
                <label for="traumacalc_trissPenetrating">Penetrating:</label>
                <input type="text" id="traumacalc_trissPenetrating" readonly>
                
                                  <label for="traumacalc_coeff">Coefficient Set:</label>
                <select id="traumacalc_coeff">
                    <option value="standard">Standard</option>
                    <option value="qmh">Queen Mary Hospital (QMH)</option>
                </select><br>
            </div>

            <!-- Buttons -->
            <button onclick="calculateAll()">Calculate</button>
            <button onclick="clearAll()">Clear</button>
        </div>';

        $renderer->doc .= $html;
        return true;
    }
}