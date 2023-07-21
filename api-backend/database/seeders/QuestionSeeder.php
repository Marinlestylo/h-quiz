<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Question;
use App\Models\Keyword;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        $q1 = Question::create([
            'name' => 'Transformation binaire',
            'content' => 'Que vaut 7 en binaire ?',
            'validation' => [
                'pattern' => '/\b(0b?)0*111\b/',
                'expected' => '0b0111'
            ],
            'options' => [
                'length' => 10
            ],
            'difficulty' => 'medium',
            'points' => 2,
            'user_id' => 1,
        ]);
        $q1->keywords()->attach(Keyword::where('name', 'binary')->get());

        // 2
        $q2 = Question::create([
            'name' => 'Transformation binaire',
            'content' => 'Que vaut `0x8` en binaire ?',
            'validation' => (object)[
                'pattern' => '/\b(0b?)0*1000\b/',
                'expected' => '0b1000'
            ],
            'difficulty' => 'medium',
            'user_id' => 1,
            'points' => 2,
            'explanation' => "`8` exprimé en hexadécimal correspond à $2^4$ soit le quatrième bit du nombre à un."
        ]);
        $q2->keywords()->attach(Keyword::where('name', 'hexadecimal')->get());

        // 3
        $q3 = Question::create([
            'name' => 'Transformation binaire',
            'content' => 'Que vaut `0` en binaire ?',
            'validation' => (object)[
                'pattern' => '/\b(0b?)0*0\b/',
                'expected' => '0'
            ],
            'difficulty' => 'easy',
            'user_id' => 1,
            'points' => 2,
            'explanation' => ''
        ]);

        // 4
        $q4 = Question::create([
            'name' => 'Complément à deux',
            'type' => 'multiple-choice',
            'options' => (object)[
                "multipleAnswers" => false
            ],
            'content' => "
Le complément à deux c'est...

## 1

Inverser tous les bits

## 2

Inverser tous les bits et ajouter 1

## 3

Inverser tous les bits et soustraire 1

## 4

Complémenter le nombre binaire pour avoir une somme de deux

## 5

Ajouter 1 puis inverer tous les bits
",
            'validation' => [2],
            'difficulty' => 'easy',
            'user_id' => 1,
            'explanation' => ''
        ]);

        // 5. Exemple de question avec des choix possibles
        // @a,b,c@ pour les listes possibles et
        // @...@ pour les champs libres
        $q5 = Question::create([
            'name' => 'Shunting Yard',
            'type' => 'fill-in-the-gaps',
            'content' => "

Dans l'algorithme de Shunting-yard dont l'image suivante résume le principe, plusieurs structures de données sont utilisées.

*-* est utilisé en entrée et *-* est utilisé en sortie. Les données intermédiaires sont stoquées sur *-*.

![algorithm](https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Shunting_yard.svg/440px-Shunting_yard.svg.png)
",
            'validation' => ["Une file d'attente", "une file d'attente", "une pile"],
            'options' => (object)[
                'gaps' => [
                    [
                        'Une pile',
                        "Une file d'attente",
                    ],
                    [
                        "une pile",
                        "une file d'attente"
                    ],
                    [
                        "une pile",
                        "une file d'attente"
                    ]
                ]
            ],
            'difficulty' => 'insane',
            'user_id' => 1,
            'explanation' => ""
        ]);

        $q6 = Question::create([
            'name' => 'Déclaration de variable',
            'content' => "Comment déclarer une variable nommée `i`, un entier 32-bits non signé égal à la valeur `42` ?",
            'validation' => (object)[
                'pattern' => '/\b(unsigned\s+int|uint32_t)\s+i\s*=\s*42\s*;?\b/',
                'expected' => 'unsigned int i = 42;'
            ],
            'options' => (object)[
                "lines" => 1,
                'chars' => 40
            ],
            'difficulty' => 'insane',
            'user_id' => 1,
            'explanation' => ""
        ]);


        $q7 = Question::create([
            'name' => 'Philostrate',
            'type' => 'multiple-choice',
            'content' => "
Témoin de la chute de [Jérusalem](https://en.wikipedia.org/wiki/Jerusalem), **Philostrate** n'hésite pas à écrire
que :

>Ce peuple s'était dès longtemps insurgé non contre les Romains, mais contre l'humanité en général. Des hommes qui ont imaginé une vie insociable, qui ne partagent avec leurs semblables ni la table, ni les libations, ni les prières, ni les sacrifices, sont plus éloignés de nous que Suse ou Bactres ou que l'Inde plus reculée encore...

Quelle eut été la portée de son argument sur **Dion Cassius** ?

## 1
Dion Cassius chante avec René
## 2
Dion Cassius n'affiche aucune sérénité
## 3
Dion Cassius adhère pleinement
## 4
La quatrième proposition est certainement la bonne
",
            'validation' => [3, 4],
            'options' => (object)['multiple' => true],
            'difficulty' => 'hard',
            'user_id' => 1,
            'explanation' => 'Aucune idée de pourquoi... Cette question ne semble pas avoir de sens'
        ]);



        Question::create([
            'name' => 'Montage électronique',
            'type' => 'fill-in-the-gaps',
            'content' => "

Dans le circuit ci-dessous. On reconnaît qu'il s'agit d'un *-*. Les deux *-* d'entrée forment un *-*. Les deux *-* de sortie forment un montage de type *-*

![circuit](https://i.stack.imgur.com/aEd8e.png)

",
            'validation' => (object)[
                "une paire différentielle",
                "transistors bipolaires",
                "amplificateur de signal",
                "transistors bipolaires",
                "push-pull"
            ],
            'options' => (object)[
                'gaps' => [
                    [
                        'une paire croisée',
                        'un gain semi-relatif',
                        'une paire différentielle',
                        'une source de courant',
                    ],
                    [
                        'transistors à effet de champ',
                        'mosfets',
                        'transistors bipolaires',
                        'résistances',
                        'IGBT',
                    ],
                    [
                        'amplificateur de signal',
                        'atténuateur de bruit',
                        'intégrateur',
                        'un étage de transconductance'
                    ],
                    [
                        'transistors bipolaires',
                        'transistors à effet de champ',
                        'mosfets',
                        'IGBT',
                        'résistances'
                    ],
                    [
                        'push-pull',
                        'différentiel',
                        'à collecteur ouvert',
                        'à descendance neutre',
                        'de Schottky'
                    ]
                ]
            ],
            'difficulty' => 'medium',
            'user_id' => 1,
            'explanation' => 'Explication'
        ]);

        Question::create([
            'name' => 'Nombres binaires signés',
            'content' => 'Expliquez comment créer un set de nombres binaires signés sur 8 bits et donnez un exemple.',
            'type' => 'long-answer',
            'validation' => [
                'expected' => 'Pour créer un ensemble de nombres binaires signés sur 8 bits, nous utilisons la représentation signée la plus courante, appelée "complément à deux". Dans cette représentation, le bit le plus significatif (le bit le plus à gauche) est utilisé comme bit de signe (0 pour les nombres positifs et 1 pour les nombres négatifs), tandis que les 7 bits restants sont utilisés pour représenter la valeur absolue du nombre.'
            ],
            'difficulty' => 'medium',
            'user_id' => 1,
        ]);

        Question::create([
            'name' => 'Boucle for en python',
            'content' => 'Faire une boucle for de 1 à 10 et afficher le résultat',
            'type' => 'code',
            'validation' => [
                'expected' => '12345678910'
            ],
            'explanation' => 'Il faut utiliser la fonction range()',
            'difficulty' => 'medium',
            'user_id' => 1,
        ]);
    }
}
