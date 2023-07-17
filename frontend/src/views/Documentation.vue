<template>
  <div class="mx-24">
    <h1 class="text-5xl mb-10">Documentation de l'application</h1>

    <!-- Quiz -->
    <div class="mb-10">
      <h2 class="text-3xl mb-4">
        Quiz
      </h2>
      <div class="text-lg">
        <p class="mb-2">
          Il y a deux types de questionnaire, les <span class="font-bold">quiz</span> et les <span
            class="font-bold">drills</span>.
        </p>
        <p class="mb-2">
          Les <span class="font-bold">quiz</span> sont des questionnaires qui peuvent être soit des quiz normaux, soit
          des examens. La différence entre les deux est qu'un autre professeur ne pourra pas
          créer d'activité à partir d'un de vos examens alors qu'il pourra tout à fait créer une activité à partir de l'un
          de vos quiz.
        </p>
        <p>
          Les <span class="font-bold">drills</span> sont des questionnaires auto-généré qui permettent aux étudiants de
          s'entraîner
          sur des types de questions.
        </p>
      </div>
    </div>


    <div class="mb-10">
      <h2 class="text-3xl mb-4">
        Questions
      </h2>
      <div class="text-lg">
        <p class="mb-4">
          Une question peut être soit publique (un autre professeur pourra l'utiliser dans un de ses quiz), soit privée
          (un
          autre professeur ne pourra pas l'ajouter à l'un de ses quiz et cette dernière n'apparaîtra pas dans le mode
          drill). Cependant, si l'une de vos questions privées apparat dans l'un de vos quiz, un autre professeur pourra
          tout de même
          créer une activité avec ce quiz.
        </p>


        <p>
          Il y a actuellement 5 types de questions possibles.
        </p>
        <p class="mb-6">
          <a class=" text-blue-300 dark:text-blue-500 hover:underline" href="#short-answer">Réponse courte</a>,
          <a class="text-blue-300 dark:text-blue-500 hover:underline" href="#long-answer">Question à développement</a>,
          <a class="text-blue-300 dark:text-blue-500 hover:underline" href="#qcm">Question à Choix Multiple</a>,
          <a class="text-blue-300 dark:text-blue-500 hover:underline" href="#fill-in-the-gaps">Texte à trou</a> et
          <a class="text-blue-300 dark:text-blue-500 hover:underline" href="#code">Question de code</a>.
        </p>

        <div class="mb-6" id="short-answer">
          <h3 class="text-2xl">
            Réponse courte
          </h3>
          <div>
            Il s'agit d'une question à laquelle l'étudiant doit répondre par une courte phrase. Cette question peut être
            corrigée automatiquement soit grâce à une comparaison entre la réponse de l'étudiant et la réponse attendue,
            soit grâce à une expression régulière.
          </div>
        </div>

        <div class="mb-6" id="long-answer">
          <h3 class="text-2xl">
            Question à développement
          </h3>
          <div>
            Il s'agit d'une question à laquelle l'étudiant doit répondre en développant son idée. Cette question n'est pas corrigée automatiquement.
          </div>
        </div>

        <div class="mb-6" id="qcm">
          <h3 class="text-2xl">
            Question à Choix Multiple
          </h3>
          <div>
            L'étudiant à le choix entre plusieurs réponses et doit choisir la bonne. Cette question est également corrigée
            automatiquement. Pour créer une réponse potentielle, il vous suffit d'écrire "## n°réponse" puis la réponse,
            comme montré dans les images ci-dessous.
          </div>
          <div class="flex mt-4 justify-around">
            <div>
              <div class="text-sm mb-2">Création de la question :</div>
              <img src="@/assets/images/documentation/qcmExplanation.png" alt="Création d'une question à choix multiple">
            </div>
            <div>
              <div class="text-sm mb-2">Preview de la question :</div>
              <img src="@/assets/images/documentation/qcmPreview.png" alt="Preview d'une question à choix multiple">
            </div>
          </div>
        </div>

        <div class="mb-6" id="fill-in-the-gaps">
          <h3 class="text-2xl">
            Texte à trou
          </h3>
          <div>
            L'étudiant à un texte à trou avec plusieurs réponses possibles pour chaque trou. Cette question est également corrigée automatiquement. Pour créer cette question, il vous suffit d'écrire le texte et de mettre "*-*" au moment où vous souhaiter qu'un trou apparaisse, comme montré dans les images ci-dessous.
          </div>
          <!-- TODO : mettre screen -->
          <div class="flex mt-4 justify-around">
            <div>
              <div class="text-sm mb-2">Création de la question :</div>
              <img src="" alt="Création d'une question à trou">
            </div>
            <div>
              <div class="text-sm mb-2">Preview de la question :</div>
              <img src="" alt="Preview d'une question à trou">
            </div>
          </div>
        </div>


        <div class="mb-6" id="code">
          <h3 class="text-2xl">
            Question de code
          </h3>
          <div>
            L'étudiant doit répondre à un petit problème en écrivant du code. Ce dernier à la possibilité de build et de compiler son code.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
